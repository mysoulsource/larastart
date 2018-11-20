<style>
    .widget-user .widget-user-image{
        top:60%;
        left:50%;
        transform:translate(-20%,-50%);
    }
    .widget-user .widget-user-image > img {
        width: 150px;
    }
</style>

<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white"
              style="background: linear-gradient(to top, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0) 59%, rgba(0, 0, 0, 0.65) 100%),url('./img/userbg.jpg'); background-size:cover; background-position:center center;height:250px;">
                <h3 class="widget-user-username">{{ form.name }}</h3>
                <h5 class="widget-user-desc">Web Designer</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" v-if="changePhoto" :src="getUserPhoto()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link " href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="activity">

                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->

                  </div>
                  <!-- /.tab-pane -->

                  <div class=" active tab-pane" id="settings">
                    <form @submit.prevent="updateInfo" @keydown="form.onKeydown($event)" class="form-horizontal">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-10">
                          <input  v-model="form.name" type="text" name="name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                          <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-10">
                          <input type="email"  v-model="form.email" name="email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputBio" class="col-sm-2 control-label">Bio</label>

                        <div class="col-sm-10">

                          <textarea name="bio"  v-model="form.bio"  class="form-control" id="inputBio" cols="30" rows="10" placeholder="Your Bio..." :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                          <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="inputPhoto" class="col-sm-2 control-label">Photo</label>

                        <div class="col-sm-10">
                          <input name="photo" @change="updatePhoto" type="file" class="form-control" id="inputPhoto" :class="{ 'is-invalid': form.errors.has('photo') }">
                          <has-error :form="form" field="photo"></has-error>
                        </div>
                      </div>

                       <div class="form-group">
                        <label for="inputPassword" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                          <input name="password" type="password" v-model="form.password" class="form-control" id="inputPassword" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                changePhoto : true,
                 form : new Form({
                    id:'',
                    name : '',
                    email: '',
                    type: '',
                    password : '',
                    photo : '',
                    bio: ''
                }),
            }
        },
        methods:{
            updateInfo(){
                this.$Progress.start();
                if(this.form.password == ''){
                    this.form.password = undefined;
                }
                this.form.put('api/profile/update')
                .then(()=>{

                    this.$Progress.finish();
                    this.changePhoto = true;
                    Fire.$emit('profileUpdated');
                })
                .catch(()=>{
                    this.$Progress.fail();
                    toast({
                        type: 'error',
                        title: 'Something Went Wrong!!'
                        })
                });
            },
            updatePhoto(e){
                this.changePhoto = false;
                //grab the image file
                let file = e.target.files[0];
                console.log(file);
                if(file['size'] < 5242880){
                    let reader = new FileReader();
                    reader.onloadend = (file)=> {
                    //after change what
                    //assign the result to form photo
                    this.form.photo = reader.result;

                    }

                    // function to change to bas64
                    reader.readAsDataURL(file);
                }else{
                    swal('Oops!!','Image size limit is 5MB','error');
                }


            },
            getUserdata(){
                axios.get('api/profile')
                .then(({data})=> (this.form.fill(data)));
            },
            getUserPhoto(){
                if(this.form.photo.length <= 100){
                     return "img/profile/" + this.form.photo
                }
            }
        },
        created(){
            this.getUserdata();
              Fire.$on('profileUpdated',()=>{
                this.getUserdata();
           });
        }
    }
</script>
