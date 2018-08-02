<table class="table invoice-table {{ $isTasks ? 'task-table' : 'product-table' }}">
<thead  {!! $isTasks ? 'style="display:none;" data-bind="visible: $root.hasTasks"' : ($invoice->has_tasks || ! empty($tasks) ? 'data-bind="visible: $root.hasItems"' : '') !!}>


    @if ($isTasks)
        <tr data-bind="visible: $root.hasItems">
            <td style="20px" colspan="20"></td>
        </tr>
    @endif




    <tr>
        <th style="min-width:32px;" class="hide-border"></th>
        <th style="min-width:120px;width:25%">{{ $invoiceLabels[$isTasks ? 'service' : 'item'] }}</th>
        <th style="width:100%">{{ $invoiceLabels['description'] }}</th>
        @if ($account->showCustomField('custom_invoice_item_label1'))
            <th style="min-width:120px">{{ $account->present()->customProductLabel1 }}</th>
        @endif
        @if ($account->showCustomField('custom_invoice_item_label2'))
            <th style="min-width:120px">{{ $account->present()->customProductLabel2 }}</th>
        @endif
        <th style="min-width:120px">{{ $invoiceLabels[$isTasks ? 'rate' : 'unit_cost'] }}</th>
        <th style="min-width:120px;display:{{ $account->hasInvoiceField($isTasks ? 'task' : 'product', $isTasks ? 'product.hours' : 'product.quantity') ? 'table-cell' : 'none' }}">{{ $invoiceLabels[$isTasks ? 'hours' : 'quantity'] }}</th>
        <th style="min-width:120px;display:{{ $account->hasInvoiceField($isTasks ? 'task' : 'product', 'product.discount') ? 'table-cell' : 'none' }}">{{ $invoiceLabels['discount'] }}</th>
        <th style="min-width:{{ $account->enable_second_tax_rate ? 180 : 120 }}px;display:none;" data-bind="visible: $root.invoice_item_taxes.show">{{ trans('texts.tax') }}</th>
        <th style="min-width:120px;">{{ trans('texts.line_total') }}</th>
        <th style="min-width:32px;" class="hide-border"></th>
    </tr>
</thead>
<tbody data-bind="sortable: { data: invoice_items_{{ $isTasks ? 'with_tasks' : 'without_tasks' }}, allowDrop: false, afterMove: onDragged} {{ $isTasks ? ', visible: $root.hasTasks' : ($invoice->has_tasks || ! empty($tasks) ? ', visible: $root.hasItems' : '') }}"
    {!! $isTasks ? 'style="display:none;border-spacing: 100px"' : '' !!}>
    <tr data-bind="event: { mouseover: showActions, mouseout: hideActions }" class="sortable-row">
        <td class="hide-border td-icon">
            <i style="display:none" data-bind="visible: actionsVisible() &amp;&amp;
                $parent.invoice_items_{{ $isTasks ? 'with_tasks' : 'without_tasks' }}().length > 1" class="fa fa-sort"></i>
        </td>
        <td>
            <div id="scrollable-dropdown-menu">
                <input type="text" data-bind="productTypeahead: product_key, items: $root.products, key: 'product_key', valueUpdate: 'afterkeydown', attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][product_key]'}" class="form-control invoice-item handled"/>
            </div>
        </td>
        <td>
            <textarea data-bind="value: notes, valueUpdate: 'afterkeydown', attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][notes]'}"
                rows="1" cols="60" style="resize: vertical;height:42px" class="form-control word-wrap"></textarea>
                <input type="text" data-bind="value: task_public_id, attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][task_public_id]'}" style="display: none"/>
                <input type="text" data-bind="value: expense_public_id, attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][expense_public_id]'}" style="display: none"/>
                <input type="text" data-bind="value: invoice_item_type_id, attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][invoice_item_type_id]'}" style="display: none"/>
        </td>
        @if ($account->showCustomField('custom_invoice_item_label1'))
            <td>
                @include('partials.custom_field', [
					'field' => 'custom_invoice_item_label1',
					'label' => $account->custom_invoice_item_label1,
					'databind' => "value: custom_value1, valueUpdate: 'afterkeydown',
                        attr: {name: 'invoice_items[" . ($isTasks ? 'T' : '') . "' + \$index() + '][custom_value1]'}",
                    'raw' => true,
				])
            </td>
        @endif
        @if ($account->showCustomField('custom_invoice_item_label2'))
            <td>
                @include('partials.custom_field', [
					'field' => 'custom_invoice_item_label2',
					'label' => $account->custom_invoice_item_label2,
					'databind' => "value: custom_value2, valueUpdate: 'afterkeydown',
                        attr: {name: 'invoice_items[" . ($isTasks ? 'T' : '') . "' + \$index() + '][custom_value2]'}",
                    'raw' => true,
				])
            </td>
        @endif
        <td>
            <input data-bind="value: prettyCost, valueUpdate: 'afterkeydown', attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][cost]'}"
                style="text-align: right" class="form-control invoice-item"/>
        </td>
        <td style="display:{{ $account->hasInvoiceField($isTasks ? 'task' : 'product', $isTasks ? 'product.hours' : 'product.quantity') ? 'table-cell' : 'none' }}">
            <input data-bind="value: prettyQty, valueUpdate: 'afterkeydown', attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][qty]'}"
                style="text-align: right" class="form-control invoice-item" name="quantity"/>
        </td>
        <td style="display:{{ $account->hasInvoiceField($isTasks ? 'task' : 'product', 'product.discount') ? 'table-cell' : 'none' }}">
            <input data-bind="value: discount, valueUpdate: 'afterkeydown', attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][discount]'}"
                style="text-align: right" class="form-control invoice-item" name="discount"/>
        </td>
        <td style="display:none;" data-bind="visible: $root.invoice_item_taxes.show">
                {!! Former::select('')
                        ->addOption('', '')
                        ->options($taxRateOptions)
                        ->data_bind('value: tax1, event:{change:onTax1Change}')
                        ->addClass($account->enable_second_tax_rate ? 'tax-select' : '')
                        ->raw() !!}
            <input type="text" data-bind="value: tax_name1, attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][tax_name1]'}" style="display:none">
            <input type="text" data-bind="value: tax_rate1, attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][tax_rate1]'}" style="display:none">
            <div data-bind="visible: $root.invoice().account.enable_second_tax_rate == '1'">
                {!! Former::select('')
                        ->addOption('', '')
                        ->options($taxRateOptions)
                        ->data_bind('value: tax2, event:{change:onTax2Change}')
                        ->addClass('tax-select')
                        ->raw() !!}
            </div>
            <input type="text" data-bind="value: tax_name2, attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][tax_name2]'}" style="display:none">
            <input type="text" data-bind="value: tax_rate2, attr: {name: 'invoice_items[{{ $isTasks ? 'T' : '' }}' + $index() + '][tax_rate2]'}" style="display:none">
        </td>
        <td style="text-align:right;padding-top:9px !important" nowrap>
            <div class="line-total" data-bind="text: totals.total"></div>
        </td>
        <td style="cursor:pointer" class="hide-border td-icon">
            <i style="padding-left:2px;display:none;" data-bind="click: $parent.removeItem, visible: actionsVisible() &amp;&amp; !isEmpty()"
            class="fa fa-minus-circle redlink" title="Remove item"/>
        </td>
    </tr>
</tbody>
</table>

<style>
    #t01 {

        border-collapse: collapse;
        width: 100%;
    }

    #t01 td, #t01 th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #t01 tr:nth-child(even){background-color: #f2f2f2;}

    #t01 tr:hover {background-color: #ddd;}

    #t01 th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #f1f1c1;

    }
</style>



<table id="t01">
    <tr>
    <tr>
        <th>Business Unit</th>
        <th>Fund</th>
        <th>Account</th>
        <th>Department</th>
        <th>Site</th>
        <th>Project</th>
        <th>Amount</th>
    </tr>
    </tr>
    <tr>
        <td contenteditable="true">00030</td>
        <td contenteditable="true">06</td>
        <td contenteditable="true">6950099</td>
        <td contenteditable="true">77100140</td>
        <td contenteditable="true">099</td>
        <td></td>
        <td class="hide-border" data-bind="css: {'hide-border': !partial()}" style="text-align: right"><span data-bind="text: totals.total"></span></td>
    </tr>
    <tr>
        <td contenteditable="true">00030</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td contenteditable="true">00030</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="7">PLEASE INDICATE COST CENTRE(S) & RETURN TO ALEXANDER MOGUTNOV AT 15 FLOOR ROOM 109</td>

    </tr>

</table>

