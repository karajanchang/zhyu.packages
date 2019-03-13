import Form from './Form';
import Toast from './Toast';

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
async function showdone(){
    let toast = new Toast();
    toast.success('資料已刪除完成');
    await sleep(1500);
    location.reload();
}

class SwalAlter{
    static delete(url, title, text, confirmText){
        swal({
            title: title,
            text: text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmText,
            closeOnConfirm: false
        }, function(){
            let form = new Form({});
            let res = form.delete(url)
                .then(response => {
                    showdone();
                });

        });
    }
}

export default SwalAlter;