/*********
 * Javascript file for Hello World ToDo List test
 * ver 1.0
 * 
 */

jQuery(document).ready(function($){

    if($(".wp-block-hw-todo-list-hw-todo-list").length){

        let pCount = 0;
        const doLoad = setInterval(function(){
            let lStr = "Loading";
            pCount ++;
            for(let c=0;c<=pCount;c++){
                lStr += ".";
            }
           pCount = (pCount === 4)?0:pCount;
            $(".wp-block-hw-todo-list-hw-todo-list").html(lStr);
            
        },500);

        const request = $.ajax({
            type: 'POST',
            url:hw_ajax_obj.ajax_url,
            data: {'action' : 'hw_todo','hw_nonce':hw_ajax_obj.nonce},
            error: function(e){ console.log(e);},
            beforeSend: function(){
                }
            });

            request.done(function(msg){
                clearInterval(doLoad);
                $(".wp-block-hw-todo-list-hw-todo-list").html(msg);
            });//end request done


}//end if block exists

        $("body").on('click','#hw_todo_submit_btn',function(){

            const request = $.ajax({
                type: 'POST',
                url:hw_ajax_obj.ajax_url,
                data: {'action' : 'hw_todo_add','hw_nonce':hw_ajax_obj.nonce,'todo_item':$('#todo-input').val()},
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    }
                });
    
                request.done(function(msg){
                    $(".wp-block-hw-todo-list-hw-todo-list").html(msg);
                });//end request done
    

        });

}); // end jquery ready