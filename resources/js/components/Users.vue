<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <!-- btn for modal calling of new user -->
                    <button class="btn btn-success" data-toggle="modal" data-target="#userAddmodal">Add New User <i class="fa fa-user-plus fa-fw"></i></button>
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
                  <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{user.type | capitalize}}</td>
                    <td>{{user.created_at | dateChange}}</td>
                    <td>
                        <a href=""> <i class="fa fa-edit text-blue"></i>   </a>
                        <a href=""> <i class="fa fa-trash text-red"></i>   </a>
                    </td>
                  </tr>
                </tbody></table>
                <!-- end of table -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- Modal for user creation -->
            <div class="modal fade" id="userAddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- form for submitting user data used vForm package-->
                <form @submit.prevent="sendUserData" @keydown="form.onKeydown($event)">
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
                        <button type="submit" class="btn btn-info">Save changes</button>
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
                form : new Form({
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
           //get the userdata from controller
           getUsers(){
               axios.get("api/user")
               .then(({ data }) => (this.users=data.data)); //using axios to get the data form controller through api url and passing it to local var
           },
           sendUserData(){
            //    sending user data to the follwing url through laravel api
                this.form.post('/api/user');

           }
       },
       created(){
           this.getUsers();
       }
    }
</script>
