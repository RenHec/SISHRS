<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="12">
      <v-toolbar flat color="success">
        <v-toolbar-title>
          Servicios asignados a mi usuario {{ usuario }}
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn class="ma-2" color="primary" @click="asignados_a_mi">
          Actualizar
        </v-btn>
      </v-toolbar>
      <br />
      <v-row>
        <template v-for="(item, index) in mis_servicios">
          <v-col cols="12" md="4" v-bind:key="index">
            <v-card class="mx-auto" max-width="344">
              <v-card-text>
                <div>
                  {{ item.type_service.name }} | {{ item.room.type_room.name }}
                </div>
                <p class="text-h4 text--primary">{{ item.client.name }}</p>
                <p>
                  Programada: {{ item.reservations_detail.arrival_date }} -
                  {{ item.reservations_detail.departure_date }}
                </p>
                <div class="text--primary">
                  {{ item.reservations_detail.description }}
                </div>
              </v-card-text>
              <hr />
              <v-list class="transparent">
                <v-list-item>
                  <v-list-item-title>Fecha de inicio</v-list-item-title>
                  <v-list-item-subtitle class="text-right">
                    {{ item.start_date }}
                  </v-list-item-subtitle>
                </v-list-item>
                <v-list-item>
                  <v-list-item-title>Hora de inicio</v-list-item-title>
                  <v-list-item-subtitle class="text-right">
                    {{ item.start_time }}
                  </v-list-item-subtitle>
                </v-list-item>
              </v-list>
              <v-card-actions>
                <v-btn
                  block
                  color="success"
                  v-if="item.start_time == null && item.end_time == null"
                  @click="start(item)"
                >
                  INICIAR
                </v-btn>
                <v-btn
                  block
                  color="danger"
                  v-if="item.start_time != null && item.end_time == null"
                  @click="end(item)"
                >
                  FINALIZAR
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </template>
      </v-row>
    </v-col>

    <v-col cols="12" md="12" v-if="mostrar">
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
            <v-toolbar-title>Asignar Servicios</v-toolbar-title>
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
          <v-btn color="info" @click="modal_asign(item)">
            Asignar
          </v-btn>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>
    </v-col>

    <v-col cols="12" md="12" v-if="mostrar">
      <v-data-table
        :headers="headers2"
        :items="desserts2"
        :search="search2"
        sort-by="calories"
        class="elevation-1"
        dark
        :footer-props="footer2"
      >
        <template v-slot:top>
          <v-toolbar flat color="primary">
            <v-toolbar-title>Servicios Asignados</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
            <v-btn class="ma-2" color="success" @click="initialize">
              Actualizar
            </v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.programado="{ item }">
          <v-list rounded dense disabled>
            <v-list-item-group>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="`Inicio: ${item.reservations_detail.arrival_date}`"
                  ></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="`Fin: ${item.reservations_detail.departure_date}`"
                  ></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </template>
        <template v-slot:item.inicio_real="{ item }">
          <v-list rounded dense disabled>
            <v-list-item-group>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="`Inicio: ${item.start_date} ${item.start_time}`"
                  ></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="`Fin: ${item.end_date} ${item.end_time}`"
                  ></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </template>
        <template v-slot:item.status="{ item }">
          <template v-if="item.start_time == null && item.end_time == null">
            NO INICIADO
          </template>
          <template
            v-else-if="item.start_time != null && item.end_time == null"
          >
            INICIADO
          </template>
          <template
            v-else-if="item.start_time != null && item.end_time != null"
          >
            FINALIZADO
          </template>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>
    </v-col>

    <v-col cols="12" md="12" class="text-center" v-if="dialog">
      <v-dialog
        v-model="dialog"
        persistent
        class="mx-auto my-12"
        max-width="700px"
      >
        <v-card color="light-green darken-4">
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
          <v-card-title>
            <span class="text-h5">Asignar | {{ selected }}</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="9">
                  <v-autocomplete
                    v-model="form.user_id"
                    :items="users"
                    chips
                    label="Seleccionar usuario"
                    outlined
                    :clearable="true"
                    :deletable-chips="true"
                    item-text="full_name"
                    item-value="id"
                    return-object
                    v-validate="'required'"
                    data-vv-scope="asignar"
                    data-vv-name="usuario"
                  ></v-autocomplete>
                  <FormError
                    :attribute_name="'asignar.usuario'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <v-col cols="12" md="3">
                  <label>Comisión %</label>
                  <vue-number-input
                    v-model="form.percentage"
                    :min="0"
                    :max="100"
                    controls
                    placeholder="comisión"
                    data-vv-scope="asignar"
                    data-vv-name="comisión"
                    v-validate="'required'"
                  ></vue-number-input>
                  <FormError
                    :attribute_name="'asignar.comisión'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" @click="dialog = false">
              Cerrar
            </v-btn>
            <v-btn color="blue darken-1" @click="validar_formulario('asignar')">
              Guardar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-col>
  </v-row>
</template>

<script>
import FormError from '../shared/FormError'

export default {
  name: 'AsignService',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      search: '',
      headers: [
        {
          text: 'Tipo de Servicio',
          align: 'start',
          value: 'room.type_room.full_name',
        },
        {
          text: 'Servicio',
          align: 'start',
          value: 'description',
        },
        {
          text: 'Cliente',
          align: 'start',
          value: 'client.name',
        },
        {
          text: 'Inicia',
          align: 'start',
          value: 'arrival_date',
        },
        {
          text: 'Finaliza',
          align: 'start',
          value: 'departure_date',
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
      users: [],
      selected: null,
      form: {
        reservations_detail_id: null,
        percentage: 0,
        user_id: null,
      },

      search2: '',
      headers2: [
        {
          text: 'Tipo de Servicio',
          align: 'start',
          value: 'room.type_room.full_name',
        },
        {
          text: 'Servicio',
          align: 'start',
          value: 'reservations_detail.description',
        },
        {
          text: 'Cliente',
          align: 'start',
          value: 'client.name',
        },
        {
          text: 'Programado',
          align: 'start',
          value: 'programado',
        },
        {
          text: 'Inicio real',
          align: 'start',
          value: 'inicio_real',
        },
        {
          text: 'Asignado a',
          align: 'start',
          value: 'user.full_name',
        },
        {
          text: 'Estado',
          align: 'start',
          value: 'status',
        },
      ],
      footer2: {
        showFirstLastPage: true,
        firstIcon: 'mdi-arrow-collapse-left',
        lastIcon: 'mdi-arrow-collapse-right',
        prevIcon: 'mdi-minus',
        nextIcon: 'mdi-plus',
      },
      desserts2: [],
      llamar: true,
      mis_servicios: [],
    }
  },

  created() {
    this.initialize()
    this.asignados_a_mi()
  },

  computed: {
    mostrar() {
      let mostrar = false
      this.$store.state.roles.forEach((element) => {
        if (element.name.toLowerCase() == 'administrador') {
          mostrar = true
        }
      })
      return (this.llamar = mostrar)
    },

    usuario() {
      return this.$store.state.usuario.full_name
    },
  },

  methods: {
    limipar() {
      this.selected = null
      this.form.reservations_detail_id = null
      this.form.percentage = 0
      this.form.user_id = null
      this.dialog = false
    },

    initialize() {
      if (this.llamar) {
        this.loading = true
        let objeto = new Object()
        objeto.id = 2
        this.$store.state.services.reservationServiceService
          .call_services(objeto)
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
            this.users = r.data.users
            this.desserts2 = r.data.asignados
            this.limipar()
            this.loading = false
          })
          .catch((r) => {
            this.loading = false
          })
      }
    },

    asignados_a_mi() {
      this.loading = true
      this.$store.state.services.reservationServiceService
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

          this.mis_servicios = r.data.data

          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    modal_asign(item) {
      this.loading = true
      this.limipar()
      this.form.reservations_detail_id = item.id
      this.selected = item.description
      this.dialog = true
      this.loading = false
    },

    validar_formulario(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.$swal({
            title: 'Agregar',
            text: '¿Está seguro de realizar esta acción?',
            type: 'success',
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.loading = true
              this.$store.state.services.reservationServiceService
                .store(this.form)
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
            }
          })
        }
      })
    },

    start(item) {
      this.$swal({
        title: 'Iniciar servicio',
        text: '¿Está seguro de realizar esta acción?',
        type: 'success',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.reservationServiceService
            .start(item)
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
              this.asignados_a_mi()
            })
            .catch((r) => {
              this.loading = false
            })
        }
      })
    },

    end(item) {
      this.$swal({
        title: 'Finalizar servicio',
        text: '¿Está seguro de realizar esta acción?',
        type: 'success',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.reservationServiceService
            .end(item)
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
              this.asignados_a_mi()
            })
            .catch((r) => {
              this.loading = false
            })
        }
      })
    },
  },
}
</script>
