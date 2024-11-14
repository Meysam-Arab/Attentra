jQuery(document).ready(function($){
	var formModal_addNewLink = $('.cd-addNewLink-modal'), addNewLink_mainNav = $('.addNewLink-main-nav');

	//open addNewLink-form form
    addNewLink_mainNav.on('click', '.cd-addNewLink',addNewLink_selected);


    //close modal
    var formModal = $('.cd-user-modal');
    formModal.on('click', function(event){
        if( $(event.target).is(formModal) || $(event.target).is('.cd-close-detail') ) {
            formModal.removeClass('is-visible');
            // jQuery('.cd-user-modal').removeClass('is-visible');
        }
    });
    //close modal when clicking the esc keyboard button
    $(document).keyup(function(event){
        if(event.which=='27'){
            formModal.removeClass('is-visible');
        }
    });


	function addNewLink_selected(){
        formModal_addNewLink.addClass('is-visible');
	}


	var login_popupMenu=$('.LoginPopupMenu'),addNewLink_LoginMenu=$('.addNewLink-main-nav2');
    addNewLink_LoginMenu.on('click','.cd-addNewLink2',addnewPopupMenuLogin_selected);
    function addnewPopupMenuLogin_selected() {
        login_popupMenu.addClass('is-visible');
    }

});

