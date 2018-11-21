<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <!-- btn for modal calling of new user -->
                    <button class="btn btn-success" @click="openaddModal">Add New User <i class="fa fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                  <!-- table for users -->
                <table class="table table-hover">
                  <tbody>
                 <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Resgistered At</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{user.type | capitalize}}</td>
                    <td>{{user.created_at | dateChange}}</td>
                    <td>
                        <a href="#" @click.prevent="openeditModal(user)"> <i class="fa fa-edit text-blue"></i>   </a>
                        <a href="#" @click.prevent="deleteUser(user.id)"> <i class="fa fa-trash text-red"></i>   </a>
                    </td>
                  </tr>
                </tbody></table>
                <!-- end of table -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="(!$gate.isAdminOrAuthor())">
            <empty></empty>
        </div>
        <!-- Modal for user creation -->
            <div class="modal fade" id="userAddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!edit" class="modal-title" id="exampleModalLabel">Add New</h5>
                        <h5 v-show="edit" class="modal-title" id="exampleModalLabel">Update User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- form for submitting user data used vForm package-->
                <form @submit.prevent="edit ? updateUserData() : sendUserData() " @keydown="form.onKeydown($event)">
                    <div class="modal-body">
                        <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                                <input v-model="form.email" type="email" name="email" placeholder="Email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                        </div>

                         <div class="form-group">

                                    <select v-model="form.type" type="email" name="type" placeholder="Type"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                        <option value="" disabled >Select User Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">Simple User</option>
                                        <option value="author">Author</option>
                                    </select>
                                <has-error :form="form" field="type"></has-error>
                        </div>

                        <div class="form-group">
                                <input v-model="form.password" type="password" name="password" placeholder="Password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" v-show="!edit" class="btn btn-info">Save changes</button>
                        <button type="submit" v-show="edit" class="btn btn-primary">Update changes</button>
                    </div>
                </form>
                <!-- end of form -->

                    </div>
                </div>
            </div>
        <!-- End of Modal -->




    </div>

</template>

<script>
    export default {
        data(){
            return {

                users:{},
                edit : false,
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

           //get Result
          getResults(page = 1) {
			axios.get('api/user?page=' + page)
				.then(response => {
					this.users = response.data;
				});
		},
           //delete User method
                deleteUser(id){
                        swal({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {

                                if (result.value) {
                                      //send ajax request to server
                                    this.form.delete('api/user/' + id).then(()=>{
                                        //fire the event trigger
                                        Fire.$emit('dataupdated');
                                            swal(
                                                'Deleted!',
                                                'Your file has been deleted.',
                                                'success'
                                                )
                                    }).catch(()=>{
                                        swal('Oops!!','Something went wrong','warning');
                                    });
                                }

                            })
            },
           //get the userdata from controller
           getUsers(){
               if(this.$gate.isAdminOrAuthor()){
                     axios.get("api/user")
               .then(({ data }) => (this.users=data)); //using axios to get the data form controller through api url and passing it to local var
               }

           },
           sendUserData(){
                if(this.$gate.isAdmin()){
                    //starting of progress bar
                        this.$Progress.start();
                    //    sending user data to the follwing url through laravel api
                        this.form.post('/api/user')
                        .then(()=>{ //promise if the request is successfull
                            //Fire a trigger to the listeners
                                Fire.$emit('dataupdated');
                                //hide the modal after user created

                                $('#userAddmodal').modal('hide');

                                //show sweet alert after success
                                toast({
                                        type: 'success',
                                        title: 'User created Successfully'
                                    })
                                })
                        .catch(()=>{
                            this.$Progress.fail();
                            swal('Oops!!','Something went wrong','warning');
                        }); //catch error if request is unsuccessful



                        this.$Progress.finish()
                }
           },
           updateUserData(){
               if(this.$gate.isAdminOrAuthor()){
                    this.$Progress.start();
                    //    sending user data to the follwing url through laravel api
                        this.form.put('/api/user/' + this.form.id)
                        .then(()=>{ //promise if the request is successfull
                            //Fire a trigger to the listeners
                                Fire.$emit('dataupdated');
                                //hide the modal after user updated

                                $('#userAddmodal').modal('hide');

                                //show sweet alert after success
                                toast({
                                        type: 'success',
                                        title: 'Updated Successfully'
                                    })
                                })
                        .catch(()=>{
                            swal('Oops!!','Something went wrong','warning');
                        }); //catch error if request is unsuccessful



                        this.$Progress.finish()
               }

           },

           openaddModal(){
               this.edit = false;
               //using vform reset function to remove all data from form
               this.form.reset();
               //show form modal
                $('#userAddmodal').modal('show');
           },
            openeditModal(user){
                this.edit = true;
               //using vform fill function to fill all data from form
                this.form.fill(user);
               //show form modal
                $('#userAddmodal').modal('show');
           }
       },
       created(){
           //run this function in start
        //    this.getUsers();
           this.getResults();
           //catch the event triggers
           Fire.$on('dataupdated',()=>{
                this.getResults();
           });
       }
    }
</script>
