<template>
  <div class="modal fade" :id="id_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel"> {{ titulo }} </h1>
          <button type="button" class="btn-close" @click="cerrarModal"></button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="cerrarModal">Cancelar</button>
          <button v-if="show_action" type="button" class="btn btn-primary" @click="guardarCambios">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default{
    props:['titulo','size','show_action','id_modal'],
    methods: {
      guardarCambios() {
        this.$emit('guardar');
      },
      cerrarModal() {
        this.$emit('cerrarModal');
      }
    }
  }
</script>