$(document).ready(() => {
    console.log('ready');
    api_url = document.location['protocol'] + '//' + document.location['hostname'] + '/';
    if (document.location['hostname'] == 'localhost') {
        api_url += 'deli/';
    }else if(document.location['hostname'].search('192.168') != -1){
        api_url += 'deli/';
    }
    $('#btn-send-message').click(() => {
        message = $('#message').val();
        customer = $('#customer').val();

        if(message != '' && customer != ''){
            sendMessage(customer, message);
        }
    });
    $('#filter-menu').on('input', () => {
        filter = $('#filter-menu').val();
        lang = $('#lang').val();
        cart_id = $('#cart_id').val();
        customer = $('#customer').val();
        $.ajax({
            url: api_url + 'filter_menu.php',
            type: 'POST',
            data: {
                filter: filter,
                lang: lang,
                cart_id: cart_id,
                customer: customer
            },
            success: function (data) {
                if(data == ''){
                    $('#menu-products').html('<p>No products found.</p>');
                }else{
                $('#menu-products').html(data);
                }
            }
        });
    });
    $('#div-add-product').hide();
    $('#btn-add-product').click(() => {
        $('#div-add-product').toggle();
    });
});
function addToCart(cart_id, productId, customer, divId) {
    divId = '#' + divId;
    $.ajax({
        url: api_url + 'add_to_cart.php',
        type: 'POST',
        data: {
            id: cart_id,
            product_id: productId,
            customer: customer
        },
        success: function (data) {
            // console.log(data);
            $('.alert').remove();
            $(divId).append(data);
        }
    });
    if (cart_id == '-1') {
        window.location.href = window.location.href;
    }
}
function removeProduct(cart_id, product_id){
    $.ajax({
        url: api_url + 'remove_product.php',
        type: 'POST',
        data: {
            cart_id: cart_id,
            product_id: product_id,
            lang: $('#lang').val()
        },
        success: function (data) {
            // console.log(data);
            $('#cart-products').html(data);
        }
    });
}
//fuction send messages
function sendMessage(customer, message, lang){
    $.ajax({
        url: api_url + 'send_message.php',
        type: 'POST',
        data: {
            customer: customer,
            message: message,
            lang: lang
        },
        success: function (data) {
            console.log(data);
            if(data != ''){
                $('#messages').empty();
                $('#message').val('');
                $('#messages').html(data);
                $('#send-message').append('<div class="alert alert-success">Thank you for the feedback.</div>');
            }
        }
    });
}