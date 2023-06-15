<div class="form-group">
    <label>اسم العميل</label>
    <a id="add_new_customer_btn" href="">(جديد <i class="fa-solid fa-circle-plus"></i>)</a>
    <select name="customer_code" id="customer_code" class="form-control ">
        <option value="{{ $customers->customer_code }}"> {{ $customers->first_name }}  {{ $customers->last_name }} </option>
    </select>
</div>
