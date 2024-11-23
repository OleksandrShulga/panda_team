<div class="form-group row align-items-center" :class="{'has-danger': errors.has('url_from_user'), 'has-success': fields.url_from_user && fields.url_from_user.valid }">
    <label for="url_from_user" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.subscribe.columns.url_from_user') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.url_from_user" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('url_from_user'), 'form-control-success': fields.url_from_user && fields.url_from_user.valid}" id="url_from_user" name="url_from_user" placeholder="{{ trans('admin.subscribe.columns.url_from_user') }}">
        <div v-if="errors.has('url_from_user')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('url_from_user') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('email_from_user'), 'has-success': fields.email_from_user && fields.email_from_user.valid }">
    <label for="email_from_user" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.subscribe.columns.email_from_user') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.email_from_user" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email_from_user'), 'form-control-success': fields.email_from_user && fields.email_from_user.valid}" id="email_from_user" name="email_from_user" placeholder="{{ trans('admin.subscribe.columns.email_from_user') }}">
        <div v-if="errors.has('email_from_user')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email_from_user') }}</div>
    </div>
</div>
