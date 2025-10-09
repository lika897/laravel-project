const selectors = {
    wrapper: '#images-wrapper',
    item: '.images-wrapper-item',
    btnRemove: '.images-wrapper-item-remove'

}
import $ from 'jquery';

$(document).ready(function () {

    $(document).on('click', '.image-wrapper-item-remove', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const url = $btn.data('url');

        $btn.addClass('disabled')

        axios.delete(url, {
            responseType:'json'
        }).then((response) => {
            console.log('response', response)

            $btn.parent().remove()
        }).catch((error)=> {
            console.error(error)
        }).finally(() => {
            $btn.removeClass('disabled')
        })




    });
});

