<template>
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5>Listado de proveedores</h5>
					<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal">
						+ Agregar
					</button>
				</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Nombre completo</th>
								<th>Email</th>
								<th>DNI</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(proveedor, index) in proveedores">
								<td> {{ index + 1 }} </td>
								<td> {{ proveedor.fullName }} </td>
								<td> {{ proveedor.email }} </td>
								<td> {{ proveedor.dni }} </td>
								<td>
									<div class="d-flex">
										<a class="btn btn-warning text-white me-2" @click.prevent="editProveedor(proveedor)"><span class="pi pi-pencil"></span></a>
										<a class="btn btn-danger" @click.prevent="eliminarProveedor(proveedor)"><span class="pi pi-trash"></span></a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<modal-component size="modal-md" :show_action="true"  id_modal="modal" :titulo="titulo" @guardar="updateOrCreate()" @cerrarModal="cerrarModal()">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="nombre">Nombre *</label>
							<input type="text" id="nombre" class="form-control" v-model="form.nombre">
							<input type="hidden" v-model="form.id">
							<span v-if="errors.nombre" class="text-danger">{{ getErrorMessage(errors.nombre) }}</span>
						</div>
						<div class="form-group col-md-6">
							<label for="ap_pat">Apellido paterno *</label>
							<input type="text" id="ap_pat" class="form-control" v-model="form.ap_pat">
							<span v-if="errors.ap_pat" class="text-danger">{{ getErrorMessage(errors.ap_pat) }}</span>
						</div>
						<div class="form-group col-md-6">
							<label for="ap_mat">Apellido materno *</label>
							<input type="text" id="ap_mat" class="form-control" v-model="form.ap_mat">
							<span v-if="errors.ap_mat" class="text-danger">{{ getErrorMessage(errors.ap_mat) }}</span>
						</div>
						<div class="form-group col-md-6">
							<label for="email">Correo electronico</label>
							<input type="email" id="email" class="form-control" v-model="form.email">
							<span v-if="errors.email" class="text-danger">{{ getErrorMessage(errors.email) }}</span>
						</div>
						<div class="form-group col-md-6">
							<label for="dni">DNI</label>
							<input type="text" id="dni" class="form-control" v-model="form.dni">
							<span v-if="errors.dni" class="text-danger">{{ getErrorMessage(errors.dni) }}</span>
						</div>
					</div>
				</modal-component>

			</div>
		</div>
	</div>
</template>
<script>
	import { Modal } from 'bootstrap'
	export default{
		mounted(){
			this.modal = new Modal("#modal");
			this.getProveedores()
		},
		data(){
			return{
				form:{
					id:'',
					nombre:'',
					ap_pat:'',
					ap_mat:'',
					email:'',
					dni:'',
				},
				titulo:'Registrar Proveedor',
				proveedores:[],
				modal: null,
				errors:{},
			}
		},
		methods:{
			getProveedores(){
				axios.get('/api/apiproveedores')
				.then(response => {
					this.proveedores = response.data;
				})
			},
			cerrarModal(){
				this.form = {
					id: '',
					nombre: '',
					ap_pat: '',
					ap_mat: '',
					email: '',
					dni: '',
				}
				this.modal.hide();
			},
			storeProveedor() {
				axios.post('/api/apiproveedores', this.form)
				.then(response => {
					this.getProveedores();
					this.form = {
						id: '',
						nombre: '',
						ap_pat: '',
						ap_mat: '',
						email: '',
						dni: '',
					}
					this.modal.hide();
					this.$toast.success('Proveedor guardado correctamente.', {
						position: 'top-right'
					})
				})
				.catch(error => {
					if (error.response && error.response.data && error.response.data.errors) {
						this.errors = error.response.data.errors;

						this.$toast.error('Corrija los siguientes errores.', {
							position: 'top-right'
						})
					} 
				});
			},
			editProveedor(proveedor){
				this.form.id = proveedor.id
				this.form.nombre = proveedor.nombre
				this.form.ap_pat = proveedor.ap_pat
				this.form.ap_mat = proveedor.ap_mat
				this.form.email = proveedor.email
				this.form.dni = proveedor.dni
				this.modal.show();
			},
			updateOrCreate() {
				if (this.form.id) {
					this.updateProveedor(this.form);
				}
				else {
					this.storeProveedor();
				}
			},
			updateProveedor() {
				axios.put('/api/apiproveedores/'+this.form.id, this.form)
				.then(response => {
					this.getProveedores();
					this.form = {
						id: '',
						nombre: '',
						ap_pat: '',
						ap_mat: '',
						email: '',
						dni: '',
					}
					this.modal.hide();
					this.$toast.success('Proveedor actualizado correctamente.', {
						position: 'top-right'
					})
				})
				.catch(error => {
					if (error.response && error.response.data && error.response.data.errors) {
						this.errors = error.response.data.errors;

						this.$toast.error('Corrija los siguientes errores.', {
							position: 'top-right'
						})
					} 
				});
			},

			eliminarProveedor(proveedor) {
				this.$swal.fire({
					title: "¿Estas seguro de eliminar al proveedor?",
					text: "Esta accion no sera reversible!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					cancelButtonText: "Cancelar",
					confirmButtonText: "Si, Eliminar!"
				}).then((result) => {
					if (result.isConfirmed) {
						axios.delete('/api/apiproveedores/'+proveedor.id)
						.then(response => {
							this.getProveedores();
							this.modal.hide();
							this.$swal.fire({
								title: "Eliminado!",
								text: "El proveedor fue eliminado correctamente.",
								icon: "success"
							});
						})
						.catch(error => {
							this.$toast.error('Hubo errores al eliminar.', {
								position: 'top-right'
							})
						});
					}
				});
				
			},
			getErrorMessage(error) {
				return Array.isArray(error) ? error[0] : error;
			},
		}
	}
</script>