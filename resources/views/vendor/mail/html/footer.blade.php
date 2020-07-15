<!--<tr>
    <td>
        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
            <tr>
                <td class="content-cell" align="center">
                    {{ Illuminate\Mail\Markdown::parse($slot) }}
                </td>
            </tr>
        </table>
    </td>
</tr>-->

<tr>
	<td>
		<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
			<tr>
				<td class="content-cell" align="center">
					<p style="font-family: Avenir, Helvetica, sans-serif; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; 
					 text-align: center;">&copy;{{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
				  
				</td>
			</tr>
		</table>
	</td>
</tr>
