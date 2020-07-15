<?php

namespace App\Shop\Orders\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Employees\Employee;
use App\Shop\Employees\Repositories\EmployeeRepository;
use App\Events\OrderCreateEvent;
use App\Mail\sendEmailNotificationToAdminMailable;
use App\Mail\SendOrderToCustomerMailable;
use App\Shop\Orders\Exceptions\OrderInvalidArgumentException;
use App\Shop\Orders\Exceptions\OrderNotFoundException;
use App\Shop\Orders\Order;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Transformers\OrderTransformable;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Shop\Tools\MarkuppriceTrait;
use DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    use OrderTransformable;
    use MarkuppriceTrait;

    /**
     * OrderRepository constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
        $this->model = $order;
    }

    /**
     * Create the order
     *
     * @param array $params
     * @return Order
     * @throws OrderInvalidArgumentException
     */
    public function createOrder(array $params) : Order
    {
        try {
            $order = $this->create($params);

            event(new OrderCreateEvent($order));

            return $order;
        } catch (QueryException $e) {
            throw new OrderInvalidArgumentException($e->getMessage(), 500, $e);
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     * @throws OrderInvalidArgumentException
     */
    public function updateOrder(array $params) : bool
    {
        try {
            return $this->update($params);
        } catch (QueryException $e) {
            throw new OrderInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return Order
     * @throws OrderNotFoundException
     */
    public function findOrderById(int $id) : Order
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new OrderNotFoundException($e);
        }
    }


    /**
     * Return all the orders
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $order, $sort);
    }
	
	/**
     * Return all the orders of customer
     *
     * @param string $order
     * @param string $sort
     * @param int $customer_id
     * @param array $columns
     * @return Collection
     */
    public function listCustomerOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*'], string $column='', $columnVal='') : Collection
    {
        $results = $this->model;
        if(!empty($column) && !empty($columnVal)){
          $results = $results->where($column,'=',$columnVal);
        }
        $results = $results->orderBy($order, $sort)->get($columns);
        
        return $results;
        
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function findProducts(Order $order) : Collection
    {
        return $order->products;
    }

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function associateProduct(Product $product, int $quantity = 1)
    {
        $catid = DB::table('category_product')->select('category_id')->where('product_id', $product->id)->first();
            
        $product_price = $this->product_price_with_markup($product->type, $product->price, $catid->category_id, Auth()->user()->id);
          
        $final_price = $this->model->getProductFinalPrice($product_price,$product->weight,$product->weight*$quantity,$quantity);
        
        $this->model->products()->attach($product, [
            'quantity' => $quantity,
            'product_name' => $product->name,
            'product_code' => $product->product_code,
            'type' => $product->type,
            'product_description' => $product->description,
            'weight' => $product->weight,
            'actual_weight' => $product->weight*$quantity,
            'weight_unit' => $product->mass_unit,
            'product_price' => $product_price,
            'final_price' => $final_price,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
      
       // $product->quantity = ($product->quantity - $quantity);
        $product->save();
    }

    /**
     * Send email to customer
     */
    public function sendEmailToCustomer()
    {
        Mail::to($this->model->customer)
            ->send(new SendOrderToCustomerMailable($this->findOrderById($this->model->id)));
    }

    /**
     * Send email notification to the admin
     */
    public function sendEmailNotificationToAdmin()
    {
        $employeeRepo = new EmployeeRepository(new Employee);
        $employee = $employeeRepo->findEmployeeById(1);

        Mail::to($employee)
            ->send(new sendEmailNotificationToAdminMailable($this->findOrderById($this->model->id)));
    }

    /**
     * @param string $text
     * @return mixed
     */
    public function searchOrder(string $text) : Collection
    {
        if (!empty($text)) {
            return $this->model->searchForOrder($text)->get();
        } else {
            return $this->listOrders();
        }
    }
	
	/**
     * @param string $text
     * @param int $customer_id
     * @return mixed
     */
    public function searchCustomerOrder(string $text, int $customer_id) : Collection
    {
        if (!empty($text)) {
            return $this->model->searchForOrder($text)->where('orders.customer_id',$customer_id)->get();
        } else {
            return $this->listOrders();
        }
    }

    /**
     * @return Order
     */
    public function transform()
    {
        return $this->transformOrder($this->model);
    }

    /**
     * @return Collection
     */
    public function listOrderedProducts() : Collection
    {
        return $this->model->products->map(function (Product $product) {
            $product->name = $product->pivot->product_name;
            $product->sku = $product->pivot->product_sku;
            $product->description = $product->pivot->product_description;
            $product->price = $product->pivot->product_price;
            $product->quantity = $product->pivot->quantity;
            return $product;
        });
    }

    /**
     * @param Collection $items
     */
    public function buildOrderDetails(Collection $items)
    {
        $items->each(function ($item) {
            $productRepo = new ProductRepository(new Product);
            
            $product = $productRepo->find($item->id);
            
            $this->associateProduct($product, $item->qty);
        });
    }
}
