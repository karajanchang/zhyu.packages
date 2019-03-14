import Form from './Form';
import ToastAlter from './ToastAlter';

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
async function showdone(toastAlter){
    toastAlter.success('資料已刪除完成');
    await sleep(1000);
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
            var toastAlter = new ToastAlter();

            let res = form.delete(url)
                .then(response => {
                    showdone(toastAlter);
                });

            res.catch( errors => {
                    toastAlter.fail(errors.message);
                }
            );
        });
    }
}

export default SwalAlter;