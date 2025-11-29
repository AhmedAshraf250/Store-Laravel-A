// (function ($) {
//     $(".item-quantity").on("change", function (e) {
//         // (e)  -->> passing the event object
//         $.ajax({
//             url: "/cart/" + $(this).data("id"), // cart/1 || custom attribute <input class="item-quantity" data-id="1">
//             method: "PUT",
//             data: {
//                 quantity: $(this).val(),
//                 _token: csrf_token,
//             },
//             success: function (response) {
//                 window.location.reload();
//             },
//         });
//     });
// })(JQuery);

// (function($){})(jQuery);

/**  This is a self-invoking (IIFE) function.
 * It defines and executes itself immediately, passing jQuery as an argument
 * and receiving it as the `$` alias inside the function.
 * This keeps `$` scoped locally and avoids conflicts with other libraries.
 */

$(".item-quantity").on("change", function (e) {
    // (e)  -->> passing the event object
    $.ajax({
        url: "/cart/" + $(this).data("id"), // cart/1 || custom attribute <input class="item-quantity" data-id="1">
        method: "PUT",
        data: {
            quantity: $(this).val(),
            _token: csrf_token,
        },
        success: function (response) {
            // TODO: handle success >> i still working
        },
    });
});

$(".remove-item").on("click", function (e) {
    let id = $(this).data("id");
    $.ajax({
        url: "/cart/" + id,
        method: "delete",
        data: {
            _token: csrf_token,
        },
        success: function (response) {
            // $(this).prev().prev().parent().remove();
            $(`#${id}`).remove();
            // TODO: handle success >> still working
        },
    });
});

// $(".add-to-cart").on("click", function (e) {
//     $.ajax({
//         url: "/cart",
//         method: "post",
//         data: {
//             product_id: $(this).data("id"),
//             quantity: $('input[name="quantity"]').val(),
//             _token: csrf_token,
//         },
//         success: function (response) {
//             alert("product added to cart");
//         },
//     });
// });
