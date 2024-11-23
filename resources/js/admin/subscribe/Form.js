import AppForm from '../app-components/Form/AppForm';

Vue.component('subscribe-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                url_from_user:  '' ,
                email_from_user:  '' ,

            }
        }
    }

});
