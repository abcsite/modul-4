
function paginationTable() {
    // alert('11111111');

    $tab = $('#show_table_pagin');
    if ($tab.attr('data_show') == 'show') {
        $tab.hide(500);
        $tab.attr('data_show', '');
    } else {
        $tab.show(500);
        $tab.attr('data_show', 'show');
    }
}

function searchInput() {
    // alert(['11','22']);

    $inp = $('#inp_text');
    var text = $inp.val();
    text = text.trim();
    if (text == '') {
        searchReturn(null);
        return;
    }
    var uri = '/articles/filter_ajax/' + text + '/';

    myAjax(uri, text, searchReturn, searchReturnErr);

}
function searchReturn(data) {

    data = JSON.parse(data);
    console.log(data);

    $tab = $('#search_list');

    if (data != null) {

        len = data.length;
        str = '';
        for ( i = 0; i < len ; i++ ) {
            str = str + '<a href="/articles/filter/?tags[]=' + data[i]['word'] + '" id="search_item' + (i + 1) + '" class="search_item">' + data[i]['word'] + '</a>';
        }
        // str = $(this).val();
        $tab.html(str);

    } else {
        $tab.html('');
    }
}

function searchReturnErr() {

    alert('Error Ajax');

}

// function searchTableClick(event) {
//     // alert('11111111');
//     str = $(this).text();
//     str = $(this).text();
//     str = '000000000';
//
//     $tab = $('#search_list');
//     $tab.html(str);
//
// }

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


function popupSubscrib() {

    $popup = $('#subscribe');
    $btn = $('#subscr_btn');

    $popup.show(2000);

    $btn.on('click', function(){
        $popup.hide(1000);
    });

}


function slider() {

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



// function popupSubscrib() {
//     var newWin = window.open('/subscribe/subscribe/subscribe/', 'Subscribe', 'width=600,height=400');
//
//     alert(newWin.location.href); // (*) about:blank, загрузка ещё не началась
//
//     newWin.onload = function() {
//
//         // создать div в документе нового окна
//         var div = newWin.document.createElement('div'),
//             body = newWin.document.body;
//
//         div.innerHTML = 'Добро пожаловать!'
//         div.style.fontSize = '30px'
//
//         // вставить первым элементом в body нового окна
//         body.insertBefore(div, body.firstChild);
//     }
//
// }