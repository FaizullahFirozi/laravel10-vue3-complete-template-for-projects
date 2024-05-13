import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function useToastr(){//create by faizullah firozi
    toastr.options.positionClass = 'toast-bottom-left';
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    return toastr;
}