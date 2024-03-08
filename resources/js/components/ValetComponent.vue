<template>
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5>Listado de Valet</h5>
					<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal">
						+ Agregar
					</button>
				</div>
				<div class="card-body">
					<div class="form-group mb-3 input-group">
						<input type="text" v-model="search" class="form-control" placeholder="Buscar por proveedor">
						<button class="btn btn-primary"><span class="pi pi-search"></span></button>
					</div>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Proveedor</th>
								<th>A favor de</th>
								<th><span class="pi pi-ellipsis-v"></span></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(valet, index) in valets">
								<td> {{ index + 1 }} </td>
								<td> {{ valet.proveedor }} </td>
								<td> {{ valet.nombre }} </td>
								<td width="20px">
									<div class="btn-group">
										<a class="btn btn-info text-white" @click.prevent="verValet(valet)" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Valet"><span class="pi pi-eye"></span></a>
										<a class="btn btn-warning text-white" @click.prevent="editVale(valet)"  data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><span class="pi pi-pencil"></span></a>
										<!-- <a class="btn btn-danger" @click.prevent="eliminarVale(valet)"  data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"><span class="pi pi-trash"></span></a> -->
										<a class="btn btn-dark" :href="'/reportes/'+valet.id" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Imprimir"><span class="pi pi-print"></span></a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<modal-component size="modal-lg" id_modal="modal" :titulo="titulo" @guardar="updateOrCreate()" :show_action="true" @cerrarModal="cerrarModal()">
					<div class="row">
						<div class="form-group col-md-6">
							<v-select 
							label="fullName"
							v-model="form.proveedor_id" 
							:options="proveedores"
							:reduce="(fullName) => fullName.id"
							></v-select>
							<input type="hidden" v-model="form.id">
							<span v-if="errors.proveedor_id" class="text-danger">{{ getErrorMessage(errors.proveedor_id) }}</span>
						</div>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" v-model="form.nombre" placeholder="A favor de *">
							<span v-if="errors.nombre" class="text-danger">{{ getErrorMessage(errors.nombre) }}</span>
						</div>
					</div>
					<hr>
					<h5>Items</h5>
					<span v-if="errors.items" class="text-danger">{{ getErrorMessage(errors.items) }}</span>
					<div class="row">
						<div class="form-group col-md-2">
							<input type="number" id="cantidad" class="form-control" v-model="formItem.cantidad" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  placeholder="Cantidad">
							<span v-if="errors.cantidad" class="text-danger">{{ getErrorMessage(errors.cantidad) }}</span>
						</div>
						<div class="form-group col-md-7">
							<input type="text" id="descripcion" class="form-control" v-model="formItem.descripcion" placeholder="Descripcion">
							<span v-if="errors.descripcion" class="text-danger">{{ getErrorMessage(errors.descripcion) }}</span>
						</div>
						<div class="form-group col-md-2">
							<input type="text" id="importe" class="form-control" v-model="formItem.importe" placeholder="importe">
							<span v-if="errors.importe" class="text-danger">{{ getErrorMessage(errors.importe) }}</span>
						</div>
						<div class="form-group col-md-1">
							<a v-if="!buton_edit" class="btn btn-success" @click.prevent="agregarItem()">
								<span class="pi pi-plus"></span>
							</a>
							<a v-else class="btn btn-warning" @click.prevent="updateItem()">
								<span class="pi pi-pencil"></span>
							</a>
						</div>
					</div>
					<hr>
					<div class="row">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>Cantidad</th>
									<th>Descripcion</th>
									<th>Importe</th>
									<th><span class="pi pi-ellipsis-v"></span></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(item, i) in items" :key="i">
									<td>{{ i + 1 }}</td>
									<td>{{ item.cantidad }}</td>
									<td>{{ item.descripcion }}</td>
									<td>{{ item.importe }}</td>
									<td>
										<a @click.prevent="editarItem(item, i)" class="btn btn-sm btn-warning text-white"><span class="pi pi-pencil"></span></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</modal-component>

				<modal-component id_modal="modal_show" :titulo="titulo" @cerrarModal="cerrarModalShow()">
					<div class="row">
						<div class="col-md-6">
							<b>Proveedor: </b>{{ form.proveedor }}
						</div>
						<div class="col-md-6">
							<b>A favor de: </b>{{ form.nombre }}
						</div>
						<div class="col-12">
							<h5>Items: <b>{{ items.length }}</b></h5>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>CANTIDAD</th>
										<th>DESCRIPCION</th>
										<th>IMPORTE</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(item, index) in items">
										<td>{{ index + 1 }}</td>
										<td>{{ item.cantidad }}</td>
										<td>{{ item.descripcion }}</td>
										<td>{{ item.importe }}</td>
									</tr>
								</tbody>
							</table>
							
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
		props:['proveedores'],
		mounted(){
			this.getValets()
			this.modal = new Modal("#modal");
			this.modal_show = new Modal("#modal_show");
		},
		data(){
			return{
				form:{
					id:'',
					nombre:'',
					proveedor_id: ''
				},
				formItem:{
					index:'',
					vale_id: '',
					importe: '',
					descripcion: '',
					cantidad: ''
				},
				items:[],
				valets:[],
				titulo:'Registrar Valet',
				modal_show: null,
				modal: null,
				errors:{},
				buton_edit: false,
				search: '',
			}
		},
		methods:{
			getValets(){
				axios.get('/api/apivalets')
				.then(response => {
					this.valets = response.data;
				})
			},
			cerrarModal(){
				this.form = {
					id: '',
					nombre: '',
					proveedor_id: ''
				}
				this.modal.hide();
			},
			cerrarModalShow(){
				this.items = []
				this.form = {
					id:'',
					nombre:'',
					proveedor_id: ''
				}
				this.modal_show.hide();
			},
			editarItem(item, index){
				this.formItem.index = index
				this.formItem.cantidad = item.cantidad
				this.formItem.descripcion = item.descripcion
				this.formItem.importe = item.importe
				this.buton_edit = true
			},
			updateItem() {
				axios.get('/api/apivalets/validateForm', {params: this.formItem})
				.then(response => {
					this.items[this.formItem.index] = response.data.data
					this.formItem = {
						index: '',
						vale_id: '',
						importe: '',
						descripcion: '',
						cantidad: ''
					};
					this.errors = {};
					this.buton_edit = false
					this.$toast.success('Item actualizado correctamente.', {
						position: 'top-right'
					});
				})
				.catch(error => {
					if (error.response && error.response.data && error.response.data.errors) {
						this.errors = error.response.data.errors;
						this.$toast.error('Corrija los siguientes errores.', {
							position: 'top-right'
						});
					}
				});
			},
			agregarItem(){
				axios.get('/api/apivalets/validateForm', {params: this.formItem})
				.then(response => {
					this.items.push(response.data.data);
					this.formItem = {
						index: '',
						vale_id: '',
						importe: '',
						descripcion: '',
						cantidad: ''
					}
					this.errors={}
					this.$toast.success('Item agregado correctamente.', {
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
			verValet(valet){
				this.form.id = valet.id
				this.form.nombre = valet.nombre
				this.form.proveedor = valet.proveedor
				this.items = valet.items
				this.modal_show.show();
			},
			
			cancelarEdicionVale(){
				this.form = {
					id: '',
					nombre: '',
					proveedor_id: ''
				}
			},
			editVale(vale){
				this.form.id = vale.id
				this.form.nombre = vale.nombre
				this.form.proveedor_id = vale.proveedor_id
				this.items = vale.items
				this.modal.show();
			},
			updateOrCreate() {
				if (this.form.id) {
					this.updateVale(this.form);
				}
				else {
					this.storeVale();
				}
			},
			storeVale() {
				axios.post('/api/apivalets', {
					nombre: this.form.nombre,
					proveedor_id: this.form.proveedor_id, 
					items: this.items 
				})
				.then(response => {
					this.getValets();
					this.form = {
						id: '',
						nombre: '',
						proveedor_id: ''
					}
					this.errors={}
					this.modal.hide();
					this.$toast.success('Guardado correctamente.', {
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
			updateVale() {
				axios.put('/api/apivalets/'+this.form.id, {
					nombre: this.form.nombre,
					proveedor_id: this.form.proveedor_id, 
					items: this.items 
				})
				.then(response => {
					this.getValets();
					this.form = {
						id: '',
						nombre: '',
						proveedor_id: ''
					}
					this.errors={}
					this.modal.hide();
					this.$toast.success('Actualizado correctamente.', {
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
			eliminarVale(proveedor) {
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
						axios.delete('/api/apivalets/'+proveedor.id)
						.then(response => {
							this.getValets();
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
			imprimir(){

			},
			getErrorMessage(error) {
				return Array.isArray(error) ? error[0] : error;
			},
		},
		computed: {
			filteredProveedores() {
				return this.proveedores.filter(proveedor =>
					proveedor.nombre.toLowerCase().includes(this.search.toLowerCase())
					);
			}
		}
	}
</script>