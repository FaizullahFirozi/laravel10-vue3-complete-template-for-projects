import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

import Swal from "sweetalert2";


export function useToastrSuccess(title = 'چاره بریالي وه!', description = ''){//create by faizullah firozi
    toastr.options.positionClass = 'toast-bottom-left';
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr.success(description,title);
    // return toastr;
}
export function useToastrError(title = 'چاره ناکامه وه!', description = 'بیا هڅه وکړئ'){//create by faizullah firozi
    toastr.options.positionClass = 'toast-bottom-left';
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr.error(description, title);
    // return toastr;
}

export function useToastrWarning(title = 'چاره تکراریږی!', description = 'مشخص مشکل'){//create by faizullah firozi
    toastr.options.positionClass = 'toast-bottom-left';
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr.warning(description, title);
    // return toastr;
}




// THIS CAN USE FOR EVERY WHERE LIKE SUCCESS, ERROR, INFO....
export function useSweetAlert(title = 'چاره بریالی وه', description = '', icon = 'success'){
     //*****Sweet Alert*****
     Swal.fire({
        position: "top-end",
        icon: icon,
        title: title,
        html: description,
        showConfirmButton: false,
        timer: 2000,
    })
}
