<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="10">
      <v-card>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" md="4">
                <v-autocomplete
                  v-model="form.type_service_id"
                  :items="type_services"
                  chips
                  label="Seleccionar tipo de servicio"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="create"
                  data-vv-name="tipo de servicio"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'create.tipo de servicio'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  counter
                  outlined
                  v-model="form.name"
                  type="text"
                  label="Nombre"
                  data-vv-scope="create"
                  data-vv-name="nombre"
                  v-validate="'required|max:50'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.nombre'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="4">
                <v-autocomplete
                  v-model="form.coin_id"
                  :items="coins"
                  chips
                  label="Seleccionar moneda"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="create"
                  data-vv-name="moneda"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'create.moneda'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="4" class="text-center">
                <label>Precio</label>
                <input
                  dark
                  placeholder="Precio"
                  class="nuevo-input"
                  v-model="form.price"
                  @input="form.price = formatear_numero($event)"
                  data-vv-name="precio"
                  v-validate="'required'"
                  data-vv-scope="create"
                />
                <FormError
                  :attribute_name="'create.precio'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="2">
                <label>Minutos</label>
                <vue-number-input
                  v-model="form.time"
                  :min="1"
                  :max="999"
                  controls
                  placeholder="Tiempo del servicio"
                  data-vv-scope="create"
                  data-vv-name="tiempo del servicio"
                  v-validate="'required'"
                ></vue-number-input>
                <FormError
                  :attribute_name="'create.tiempo del servicio'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="validar_formulario('create')"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>

    <v-col cols="12" md="12">
      <v-data-table
        :headers="headers"
        :items="desserts"
        :search="search"
        sort-by="calories"
        class="elevation-1"
        dark
        :footer-props="footer"
      >
        <template v-slot:top>
          <v-toolbar flat color="success">
            <v-toolbar-title>Tipos de Mensaje</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
            <v-btn class="ma-2" color="primary" @click="initialize">
              Actualizar
            </v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn
            class="ma-2"
            text
            icon
            color="orange lighten-2"
            v-if="!item.deleted_at"
            @click="mapear(item)"
          >
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn
            class="ma-2"
            text
            icon
            :color="item.deleted_at ? 'blue lighten-2' : 'red lighten-2'"
            @click="destroy(item)"
          >
            <v-icon
              v-text="item.deleted_at ? 'mdi-thumb-up' : 'mdi-thumb-down'"
            ></v-icon>
          </v-btn>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script>
import FormError from '../shared/FormError'

export default {
  name: 'TypeMessage',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      editedIndex: false,
      search: '',
      headers: [
        {
          text: 'Servicio',
          align: 'start',
          value: 'type_service.name',
        },
        {
          text: 'Nombre',
          align: 'start',
          value: 'name',
        },
        {
          text: 'Tiempo',
          align: 'start',
          value: 'time',
        },
        {
          text: 'Moneda',
          align: 'start',
          value: 'coin.symbol',
        },
        {
          text: 'Precio',
          align: 'start',
          value: 'price',
        },
        { text: 'Opciones', value: 'actions', sortable: false },
      ],
      footer: {
        showFirstLastPage: true,
        firstIcon: 'mdi-arrow-collapse-left',
        lastIcon: 'mdi-arrow-collapse-right',
        prevIcon: 'mdi-minus',
        nextIcon: 'mdi-plus',
      },
      desserts: [],
      coins: [],
      type_services: [],
      form: {
        id: 0,
        name: null,
        time: null,
        price: null,
        coin_id: null,
        type_service_id: null,
      },
    }
  },
  computed: {
    formTitle() {
      return !this.editedIndex
        ? 'Agregar Tipo de Masaje'
        : 'Editar Tipo de Masaje'
    },
  },

  watch: {
    dialog(val) {
      val || this.close()
    },
  },

  created() {
    this.initialize()
    this.getTypeService()
    this.getCoin()
  },

  methods: {
    formatear_numero(n) {
      if (n) {
        let numero = n.target.value.replace(',', '')
        numero = !isNaN(numero)
          ? parseInt(numero).toLocaleString('de-DE', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 0,
            })
          : null
        return !isNaN(numero) ? numero.replace('.', ',') : null
      } else {
        return null
      }
    },

    limpiar() {
      this.editedIndex = false

      this.form.id = 0
      this.form.name = null
      this.form.time = null
      this.form.price = null
      this.form.coin_id = null
      this.form.type_service_id = null

      this.$validator.reset()
      this.$validator.reset()
    },

    initialize() {
      this.loading = true

      this.$store.state.services.typeMessageService
        .index()
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, 'Mensaje')
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, 'Mensaje')
              }
            }
            this.loading = false
            return
          }

          this.desserts = r.data.data
          this.close()
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    mapear(item) {
      this.form.id = item.id
      this.form.name = item.name
      this.form.time = item.time
      this.form.coin_id = item.coin
      this.form.type_service_id = item.type_service
      this.form.price = item.price

      this.editedIndex = true
    },

    close() {
      this.limpiar()
    },

    validar_formulario(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result)
          this.editedIndex ? this.update(this.form) : this.store(this.form)
      })
    },

    destroy(data) {
      let title = !data.deleted_at ? 'Desactivar' : 'Activar'
      let type = !data.deleted_at ? 'error' : 'success'
      this.$swal({
        title: title,
        text: '¿Está seguro de realizar esta acción?',
        type: type,
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.typeMessageService
            .destroy(data)
            .then((r) => {
              this.loading = false

              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, 'Mensaje')
                  }
                }
                return
              }

              this.$toastr.success(r.data, 'Mensaje')
              this.initialize()
            })
            .catch((r) => {
              this.loading = false
            })
        } else {
          this.close()
        }
      })
    },

    store(data) {
      this.$swal({
        title: 'Agregar',
        text: '¿Está seguro de realizar esta acción?',
        type: 'success',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.typeMessageService
            .store(data)
            .then((r) => {
              this.loading = false

              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, 'Mensaje')
                  }
                }
                return
              }

              this.$toastr.success(r.data, 'Mensaje')
              this.initialize()
            })
            .catch((r) => {
              this.loading = false
            })
        } else {
          this.close()
        }
      })
    },

    update(data) {
      this.$swal({
        title: 'Modificar',
        text: '¿Está seguro de realizar esta acción?',
        type: 'warning',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.typeMessageService
            .update(data)
            .then((r) => {
              this.loading = false

              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, 'Mensaje')
                  }
                }
                return
              }

              this.$toastr.success(r.data, 'Mensaje')
              this.initialize()
            })
            .catch((r) => {
              this.loading = false
            })
        } else {
          this.close()
        }
      })
    },

    getTypeService() {
      this.$store.state.services.typeServiceService
        .index()
        .then((r) => {
          this.type_services = r.data.data
        })
        .catch((r) => {})
    },

    getCoin() {
      this.$store.state.services.coinService
        .index()
        .then((r) => {
          this.coins = r.data.data
        })
        .catch((r) => {})
    },
  },
}
</script>
