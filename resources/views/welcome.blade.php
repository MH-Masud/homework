<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <br><br>
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    <select class="form-control" id="product">
                        <option>Select Product</option>
                        @foreach($products as $product)
                        <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-1">
             <input type="hidden" name="id" id="id" class="form-control">
         </div>
         <div class="col-lg-2">
             <input type="text" name="name" id="name" class="form-control" readonly>
         </div>
         <div class="col-lg-1">
             <input type="text" name="price" id="price" class="form-control" readonly>
         </div>
         <div class="col-lg-2">
             <input type="text" name="color" id="color" class="form-control" readonly>
         </div>
         <div class="col-lg-2">
             <input type="text" name="unit" id="unit" class="form-control" readonly>
         </div>
         <div class="col-lg-2">
             <button id="add_btn" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span></button>
         </div>
     </div><br>
     <div class="row">
        <div class="col-lg-6 col-lg-offset-2">
            <table class="table table-bordered" id="data_table">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>ID</th>
                        <th>Price</th>
                        <th>Color</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button class="btn btn-info btn-lg" id="add_cart_btn">Add To Cart</button>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div id="show_data"></div>
                <button id="save_btn" class="btn btn-lg btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#product').change(function(){
            var id = $(this).val();
            // alert(id);
            $.ajax({
                url:"{{ route('product') }}",
                method:"get",
                data:{id:id},
                dataType:'json',
                success:function(html){
                    $('#id').val(html.id);
                    $('#name').val(html.name);
                    $('#price').val(html.price);
                    $('#color').val(html.color);
                    $('#unit').val(html.unit);
                }
            })
        });
        $('#add_btn').click(function(){
            var row_count = $('#data_table tbody').find('tr').length+1;
            var id = $('#id').val();
            var name = $('#name').val();
            var price = $('#price').val();
            var color = $('#color').val();
            var unit = $('#unit').val();
            var add_row = "<tr><td>"+row_count+"</td><td>"+name+"</td><td>"+id+"</td><td>"+price+"</td><td>"+color+"</td><td>"+unit+"</td><td><input id='remove_btn' type='button' class='btn btn-sm btn-success' value='X'></td></tr>";
            $('#data_table tbody').append(add_row);
        });
        $('#data_table tbody').on('click','#remove_btn',function(){
            $(this).parent().parent().remove();
        });
        $('#add_cart_btn').click(function(){
            var table_data = [];
            $('#data_table tr').each(function(row,tr){
                if ($(tr).find('td:eq(1)').text() != '') {
                    var sub = {
                        'id':$(tr).find('td:eq(2)').text(),
                        'name':$(tr).find('td:eq(1)').text(),
                        'price':$(tr).find('td:eq(3)').text(),
                        'color':$(tr).find('td:eq(4)').text(),
                        'unit':$(tr).find('td:eq(5)').text()
                    }
                    table_data.push(sub);
                }
            });
            $.ajax({
                url:"{{ route('cart') }}",
                method:"get",
                data:{table_data:table_data},
                // dataType:'json',
                success:function(html){
                    get_table_data();
                }
            });
        });
        function get_table_data()
        {
            $.ajax({
                url:"{{ route('get') }}",
                method:"get",
                success:function(data)
                {
                    $('#show_data').html(data);
                }
            })
        }
        get_table_data();
        $('#save_btn').click(function(){
            // alert('in');
            $.ajax({
                url:"{{ route('save') }}",
                method:"get",
                success:function(){
                    get_table_data();
                }
            });
        });
    });
</script>
</body>
</html