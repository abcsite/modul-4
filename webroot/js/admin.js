function confirmDelete() {

    if (confirm('Delete this item?')) {
        return true;
    } else {
        return false;
    }
}

function myAjax(uri, data, success, error) {
    // $(this).text("77777777777777777");
    // alert(event.target.innerHTML);
    $.ajax({
        url: uri,
        type: "POST",
        data: JSON.stringify(data),
        // dataType: "text",
        error: error,
        success: success
    })
}

function error() {
    alert('Error ajax');
}

// function categoryDel(uri) {
//     myAjax(uri, '', success, error);
//     function success(result) {
//         var res = JSON.parse(result);
//         if (res) {
//             $(this).parent().parent().hide();
//         }
//         return false;
//     }
// }

function updateCategoryByArticle(res) {
    res = JSON.parse(res);

    if (res[2] == 'add') {
        $('#cat_id_add' + res[0]).hide();
        $('#cat_id_del' + res[0]).show();
    } else {
        $('#cat_id_add' + res[0]).show();
        $('#cat_id_del' + res[0]).hide();
    }

}

/function slider() {

    $img = $('.slider__item');

    i = 0;
    n = 7;
    id = setInterval(function () {
        $img.eq(i).fadeOut(3000);
        // rand = Math.ceil();
        i = (i + 1) % n;
        $img.eq(i).fadeIn(3000);

    },5000);


    $img.on('click', function(){
        $img.eq(1).hide(1000);
    });

}

function menuShow() {

    $popup = $('#subscribe');
    $btn = $('#subscr_btn');

    $popup.show(2000);

    $btn.on('click', function(){
        $popup.hide(1000);
    });

}




function closeIt()
{
    alert('oooooo');
    return "Пожалуйста, не закрывайте меня!";
}
window.onbeforeunload = closeIt;



$(document).ready(function () {
    $online = $('#online');
    $visited = $('#visited');

    time_id = setInterval(function () {
        rand = Math.ceil(Math.random() * 5) ;
        $online.html(rand);
        visited = rand + parseInt($visited.html());
        $visited.html(visited);
    },3000);

    time_subscrib = setTimeout(function () {
        popupSubscrib();
    }, 15000);

    slider();

    //
    // $(window).unload(function(){
    //     alert("Пока, пользователь!");
    // });

})

