class ToastAlter{
    constructor() {

    }
    success(message){
        $.toast({
            heading: '成功!',
            text: message,
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'success',
            hideAfter: 4000,
            stack: 6
        });
    }

    fail(message){
        $.toast({
            heading: '失敗!',
            text: message,
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'danger',
            hideAfter: 4000,
            stack: 6
        });
    }
}

export default ToastAlter;