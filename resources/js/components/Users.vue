<template>
	<div class="container">
		<div class="row mt-5">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Fixed Header Table</h3>

						<div class="card-tools">
							<button class="btn btn-success" type="button" @click="newModal">
								<i class="fas fa-user-plus"></i>
							</button>`

						  	<!-- Modal -->
						  	<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalTitle" aria-hidden="true">
							  	<div class="modal-dialog modal-dialog-centered" role="document">
									<div class="modal-content">
									  	<div class="modal-header">
											<h5 v-show="!edit_mode" class="modal-title" id="exampleModalLongTitle">Add User</h5>
											<h5 v-show="edit_mode" class="modal-title" id="exampleModalLongTitle">Edit User</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										  		<span aria-hidden="true">&times;</span>
									  		</button>
								  		</div>
								  		<form @submit.prevent="edit_mode ? updateUser() : createUser()">
										  	<div class="modal-body">
												<div class="form-group">
											      	<input v-model="form.name" 
											      			placeholder="Name" 
											      			type="text" 
											      			name="name"
											        		class="form-control" 
											        		:class="{ 'is-invalid': form.errors.has('name') }">

											      	<has-error :form="form" field="name"></has-error>
											    </div>

											    <div class="form-group">
											      	<input v-model="form.email" 
											      			placeholder="Email Address" 
											      			type="text" 
											      			name="email"
											        		class="form-control" 
											        		:class="{ 'is-invalid': form.errors.has('email') }">

											      	<has-error :form="form" field="email"></has-error>
											    </div>

											    <div class="form-group">
											      	<input v-model="form.password" 
											      			placeholder="Password" 
											      			type="password" 
											      			name="password"
											        		class="form-control" 
											        		:class="{ 'is-invalid': form.errors.has('password') }">

											      	<has-error :form="form" field="password"></has-error>
											    </div>

											    <div class="form-group">
											    	<select v-model="form.type" 
											      			name="type"
											        		class="form-control" 
											        		:class="{ 'is-invalid': form.errors.has('type') }">
											        	<option value="">Select Type</option>
											        	<option value="admin">Admin</option>
											        	<option value="user">User</option>
											        	<option value="author">Author</option>
											    	</select>

											      	<has-error :form="form" field="type"></has-error>
											    </div>

											    <div class="form-group">
											      	<textarea v-model="form.bio" 
											      			placeholder="Short Bio" 
											      			type="text" 
											      			name="bio"
											        		class="form-control" 
											        		:class="{ 'is-invalid': form.errors.has('bio') }">	
											        </textarea>

											      	<has-error :form="form" field="bio"></has-error>
											    </div>

											    <!-- <div class="form-group">
											      	<input v-model="form.photo" 
											      			placeholder="Email Address" 
											      			type="file" 
											      			name="email"
											        		class="form-control" 
											        		:class="{ 'is-invalid': form.errors.has('photo') }">

											      	<has-error :form="form" field="photo"></has-error>
											    </div> -->

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
												<button v-show="!edit_mode" type="submit" class="btn btn-primary">Create</button>
												<button v-show="edit_mode" type="submit" class="btn btn-success">Update</button>
											</div>
										</form>
									</div>
								</div>
							</div>							
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<table class="table table-head-fixed">
							<thead>
								<tr>
									<th>ID</th>
									<th>User</th>
									<th>Email</th>
									<th>Type</th>
									<th>Registered At</th>
									<th>Modify</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(user, index) in users" :key="index">
									<td>{{user.id}}</td>
									<td>{{user.name | capitalize}}</td>
									<td>{{user.email}}</td>
									<td><span class="tag tag-danger">{{user.type | capitalize}}</span></td>
									<td>{{user.created_at | myDate}}</td>
									<td>
										<a href="#" @click="editModal(user)">
											<i class="fa fa-edit blue"></i>
										</a>
										&nbsp;&nbsp;
										<a href="#" @click="deleteUser(user.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				edit_mode : false, 
				users: {},
				form : new Form({
					id   	: '',
					name	: '',
					email 	: '',
					password: '',
					type 	: '',
					bio 	: '',
					photo 	: '',
				}),
			}
		},
		methods : {
			updateUser(){
				this.$Progress.start();

				this.form.put('api/user/'+this.form.id)
				.then(()=>{
					$('#userModal').modal('hide');
					Toast.fire({
					  icon: 'success',
					  title: 'Record has been updated'
					});
					Fire.$emit('AfterCreate');
					this.$Progress.finish();
				})
				.catch(()=>{
					this.$Progress.fail();
				});
			},

			newModal(){
				this.edit_mode = false;
				this.form.reset();
				this.form.clear();
				$('#userModal').modal('show');
			},

			editModal(user){
				this.edit_mode = true;
				this.form.reset();
				this.form.clear();
				this.form.fill(user);
				$('#userModal').modal('show');
			},

			deleteUser(id){
				Swal.fire({
					title: 'Are you sure to delete this record?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						this.$Progress.start();
						// delete request
						this.form.delete('api/user/'+id)
						.then(()=>{
							Toast.fire({
							  icon: 'success',
							  title: 'Record has been deleted'
							});
							Fire.$emit('AfterCreate');
							this.$Progress.finish();
						})
						.catch(()=>{
							this.$Progress.fail();
						});
					}
				})
			},

			loadUsers(){
				axios.get('api/user').then(
					({ data }) => (this.users = data.data)
				);
			},

			createUser(){
				this.$Progress.start();

				this.form.post('api/user')
				.then(() => {
					$('#userModal').modal('hide');
					Toast.fire({
					  icon: 'success',
					  title: 'User created successfully'
					});
					Fire.$emit('AfterCreate');
					this.$Progress.finish();
				})
				.catch(() => {
					this.$Progress.fail();
				});

			}
		},

		created() {
			this.loadUsers();
			Fire.$on('AfterCreate', ()=>{
				this.loadUsers();
			});
		}
	}
</script>
