import Form from './Form';
import Toast from './Toast';

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
            let toast = new Toast();
            let res = form.delete(url)
                .then(response => {
                    toast.success('資料已刪除完成');
                });

        });
    }
}

export default SwalAlter;