function updateMedia(id) {
    $('#modalEditMedia').modal('hide');
    $.ajax({url: '/account/edit-media/' + id, type: 'POST', dataType: 'json', data: $('#formEditMedia').serialize()}).done(function(data) {
        if(data.status == 'success') {
            getCommentList(id);
        }
        else {
            alert(data.message);
        }
    });
}
function deleteMedia(id) {
    $('#entry' + id).hide();
    $.ajax({url: '/account/delete-media', dataType: 'json', type: 'POST', data: 'id=' + id}).done(function(data) {
        if(data.status == 'error') {
            $('#entry' + id).show();
        }
    });
}

function like(id) {
    var likeCount = $('#item' + id + ' .like_count');
    var isLike    = $('#item' + id).attr('data-liked') != '1';
    if(isLike) {
        $('#item' + id + ' i').addClass('text-danger');
        likeCount.html(parseInt(likeCount.html()) + 1);
    }
    else {
        $('#item' + id + ' i').removeClass('text-danger');
        likeCount.html(parseInt(likeCount.html()) - 1);
    }
    var url = (isLike) ? '/account/like' : '/account/unlike';
    $.ajax({url: url, dataType: 'json', type: 'POST', data: 'id=' + id}).done(function(data) {
        if(data.status == 'error') {
            if(isLike) {
                $('#item' + id + ' i').removeClass('text-danger');
                likeCount.html(parseInt(likeCount.html()) - 1);
            }
            else {
                $('#item' + id + ' i').addClass('text-danger');
                likeCount.html(parseInt(likeCount.html()) + 1);
            }
        }
        else {
            $('#item' + id).attr('data-liked', isLike ? '1' : '0');
        }
    });
}


function follow(id) {
    btn     = $('#btnUserFollow');
    oldHtml = btn.html();
    btn.html('Bekleyin..');
    $.ajax({url: '/account/follow', dataType: 'json', type: 'POST', data: 'id=' + id}).done(function(data) {
        if(data.status == 'error') {
            btn.html(oldHtml);
        }
        else {
            btn.attr("onclick", "unfollow('" + id + "')");
            if(data.is_private == 1) {
                btn.html('<i class="fa fa-clock-o"></i> İstek Gönderildi').removeClass('btn-default').removeClass('btn-success').removeClass('btn-primary').addClass('btn-default');
            } else {
                btn.html('<i class="fa fa-check"></i> Takip Ediyorsun').removeClass('btn-default').removeClass('btn-success').removeClass('btn-primary').addClass('btn-success');
            }
        }
    });
}


function block(id) {
    if(confirm('Emin misin?')) {
        btn     = $('#btnUserFollow');
        oldHtml = btn.html();
        btn.html('Bekleyin..');
        $.ajax({url: '/account/block', dataType: 'json', type: 'POST', data: 'id=' + id}).done(function(data) {
            if(data.status == 'error') {
                btn.html(oldHtml);
            }
            else {
                $('#btnPopBlock').css('display', 'none');
                $('#btnPopUnblock').css('display', '');
                btn.attr("onclick", "unblock('" + id + "')");
                btn.html('<i class="fa fa-unlock"></i> Engellemeyi Kaldır').removeClass('btn-default').removeClass('btn-success').removeClass('btn-primary').addClass('btn-default');
            }
        });
    }
}

var arrCommentLikersData = [];

function setCommentListByIndex(index) {
    contentDiv    = $('.lg-outer .lg-item:eq(' + index + ') .fb-comments');
    id            = contentDiv.attr('data-id');
    carouselIndex = contentDiv.attr('data-carousel-index');
    if(carouselIndex) {
        setCommentList(id, carouselIndex);
    } else {
        setCommentList(id);
    }
}

function getCommentList(id,carouselIndex) {
    $.ajax({url: '/account/load-comments/' + id, type: 'GET'}).done(function(data) {
        if(carouselIndex){
            data = '<h1 style="margin-top:0;">Carousel</h1><h6>' + $('#comments' + id).attr('data-carousel-text') + '</h6>' + data;
        }
        arrCommentLikersData[id] = data;
        setCommentList(id);
    });
}

function setCommentList(id, carouselIndex) {
    if(!arrCommentLikersData[id]) {
        return getCommentList(id,carouselIndex);
    }
    $commentContainer = $('#comments' + id);
    gHtml             = arrCommentLikersData[id];
    if(carouselIndex && carouselIndex != 0) {
        // Nothing yet
        // $commentContainer = $('#comments' + id + carouselIndex);
        // $commentContainer.html('<h1>Carousel</h1><h6>' + $commentContainer.attr('data-carousel-text') + '</h6>');
    }
    else {
        $commentContainer.html(gHtml);
        if(window.innerWidth < 992) {
            $('#comments' + id + ' .lazy').show().lazyload({container: $('.lg-current'), threshold: 500}).removeClass("lazy");
        }
        else {
            $('#comments' + id + ' .lazy').show().lazyload({container: $commentContainer, threshold: 500}).removeClass("lazy");
        }
    }
}