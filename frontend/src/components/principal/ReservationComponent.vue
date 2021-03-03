<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="12">
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
              <v-col cols="12" :md="!bloquear ? '2' : '3'"></v-col>

              <v-col cols="12" md="3">
                <v-autocomplete
                  v-model="servicios"
                  :items="listar_servicios"
                  chips
                  label="Seleccionar servicios"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  multiple
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" md="2">
                <v-menu
                  ref="menu_inicio"
                  v-model="menu_inicio"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
                  :disabled="bloquear"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="form.arrival_date"
                      label="Fecha de inicio"
                      persistent-hint
                      counter
                      outlined
                      v-bind="attrs"
                      @input="formatear_fecha_inicio"
                      v-on="on"
                      :disabled="bloquear"
                      data-vv-scope="create"
                      data-vv-name="fecha de inicio"
                      v-validate="'required|date_format:yyyy-mm-dd'"
                    ></v-text-field>
                    <FormError
                      :attribute_name="'create.fecha de inicio'"
                      :errors_form="errors"
                    ></FormError>
                  </template>
                  <v-date-picker
                    v-model="form.arrival_date"
                    :min="new Date().toISOString().substr(0, 10)"
                    no-title
                    :disabled="bloquear"
                    @input="menu_inicio = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>

              <v-col cols="12" md="2">
                <v-menu
                  ref="menu_fin"
                  v-model="menu_fin"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  offset-y
                  :disabled="bloquear"
                  max-width="290px"
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="form.departure_date"
                      label="Fecha de fin"
                      persistent-hint
                      counter
                      outlined
                      v-bind="attrs"
                      @input="formatear_fecha_fin"
                      v-on="on"
                      :disabled="bloquear"
                      data-vv-scope="create"
                      data-vv-name="fecha de finalización"
                      v-validate="'required|date_format:yyyy-mm-dd'"
                    ></v-text-field>
                    <FormError
                      :attribute_name="'create.fecha de finalización'"
                      :errors_form="errors"
                    ></FormError>
                  </template>
                  <v-date-picker
                    v-model="form.departure_date"
                    :min="form.arrival_date"
                    no-title
                    :disabled="bloquear"
                    @input="menu_fin = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="12" md="1" v-if="!bloquear">
                <v-btn
                  color="warning darken-1"
                  x-large
                  @click="buscar_habitacion('create')"
                  >Buscar habitaciones</v-btn
                >
              </v-col>
              <v-col cols="12" :md="!bloquear ? '2' : '3'"></v-col>
            </v-row>

            <v-row>
              <v-col
                cols="12"
                md="4"
                v-for="(item, index) in habitaciones"
                v-bind:key="index"
              >
                <v-card :loading="loading_room" class="mx-auto my-12">
                  <template slot="progress">
                    <v-progress-linear
                      color="deep-purple"
                      height="10"
                      indeterminate
                    ></v-progress-linear>
                  </template>

                  <v-img height="250" :src="item.photo"></v-img>

                  <v-card-title>{{ item.name }}</v-card-title>

                  <v-card-text>
                    <div v-html="item.description"></div>
                  </v-card-text>

                  <v-divider class="mx-4"></v-divider>

                  <v-card-title>Información extra</v-card-title>

                  <v-card-text>
                    <v-chip-group column>
                      <v-chip>{{ item.type_room }}</v-chip>

                      <v-chip>{{ "Personas: " + item.amount_people }}</v-chip>

                      <v-chip>{{ item.type_bed }}</v-chip>
                    </v-chip-group>
                  </v-card-text>

                  <v-divider class="mx-4"></v-divider>

                  <v-card-title>Precios</v-card-title>

                  <v-card-text>
                    <v-chip-group
                      active-class="success accent-4 white--text"
                      column
                    >
                      <template v-for="(room, x) in todos_precios">
                        <v-chip
                          v-if="room.id == item.id"
                          v-bind:key="x"
                          @click="seleccionar_precio(room)"
                          >{{ room.name }}</v-chip
                        >
                      </template>
                    </v-chip-group>
                  </v-card-text>

                  <v-divider v-if="todos_masajes.length > 0" class="mx-4"></v-divider>

                  <v-card-title v-if="todos_masajes.length > 0">Masajes</v-card-title>

                  <v-card-text v-if="todos_masajes.length > 0">
                    <v-chip-group
                      column
                    >
                      <template v-for="(masaje, x) in todos_masajes">
                        <v-chip
                          v-if="masaje.id == item.id"
                          v-bind:key="x"
                          >{{ masaje.name }}</v-chip
                        >
                      </template>
                    </v-chip-group>
                  </v-card-text>

                  <v-divider class="mx-4"></v-divider>

                  <v-card-actions>
                    <v-btn
                      color="green lighten-2"
                      v-if="!item.esconder"
                      @click="reservar(item)"
                    >
                      Reservar
                    </v-btn>
                    <v-btn
                      color="red lighten-2"
                      v-if="item.esconder"
                      @click="eliminar_reservacion(item)"
                    >
                      Eliminar reservación
                    </v-btn>
                    <v-btn
                      v-if="item.esconder"
                      color="primary lighten-2"
                      @click="promociones(item, item)"
                    >
                      Promociones
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="limpiar">Cancelar</v-btn>
          <v-btn color="blue darken-1" text @click="agregar">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>

    <v-dialog v-model="dialog" width="40%" persistent color="primary">
      <v-card>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">Promociones</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" md="12">
                <v-autocomplete
                  v-model="promocion"
                  :items="data_promociones"
                  chips
                  label="Seleccionar promoción"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="promo"
                  data-vv-name="promoción"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'promo.promoción'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" @click="dialog = false">Cancelar</v-btn>
          <v-btn color="blue darken-1" @click="aplicar_promocion('promo')"
            >Aplicar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialog_cliente" width="55%" persistent color="primary">
      <v-card>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">Cliente de la reservación</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" md="12">
                <v-autocomplete
                  v-model="form.client_id"
                  :items="clientes"
                  chips
                  label="Seleccionar cliente"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="full_name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="promo"
                  data-vv-name="cliente"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'reservar.cliente'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" @click="dialog_cliente = false"
            >Cancelar</v-btn
          >
          <v-btn color="blue darken-1" @click="guardar_reservacion('reservar')"
            >Aplicar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<style scoped>
.quill-editor,
.content {
  background-color: white;
  color: black;
}
</style>

<script>
import FormError from "../shared/FormError";
import moment from "moment";

export default {
  name: "Reservation",
  components: {
    FormError,
    moment,
  },
  data() {
    return {
      loading: false,
      loading_room: false,
      dialog: false,
      dialog_cliente: false,
      editedIndex: false,
      menu_inicio: false,
      menu_fin: false,
      bloquear: false,
      promocion: null,
      index: null,
      data_promociones: [],
      habitaciones: [],
      items: [],
      clientes: [],
      servicios: [],
      listar_servicios: [],
      todos_precios: [],
      seleccionados: [],
      todos_masajes: [],
      form: {
        id: 0,
        nit: null,
        name: null,
        ubication: null,
        arrival_date: null,
        departure_date: null,
        client_id: null,
        coin_id: null,
        details: [],
      },
    };
  },
  computed: {
    formTitle() {
      return !this.editedIndex ? "Agregar Reservación" : "Editar Reservación";
    },
  },

  created() {
    this.getServicios();
  },

  methods: {
    //formato de fecha
    formatear_fecha_inicio(date) {
      if (!date) return null;

      const [month, day, year] = date.split("/");
      this.form.arrival_date = `${year}-${month.padStart(
        2,
        "0"
      )}-${day.padStart(2, "0")}`;
    },

    formatear_fecha_fin(date) {
      if (!date) return null;

      const [month, day, year] = date.split("/");
      this.form.departure_date = `${year}-${month.padStart(
        2,
        "0"
      )}-${day.padStart(2, "0")}`;
    },

    formatear_numero(n) {
      if (n) {
        let numero = n.target.value.replace(",", "");
        numero = !isNaN(numero)
          ? parseInt(numero).toLocaleString("de-DE", {
              minimumFractionDigits: 0,
              maximumFractionDigits: 0,
            })
          : null;
        return !isNaN(numero) ? numero.replace(".", ",") : null;
      } else {
        return null;
      }
    },

    limpiar() {
      this.editedIndex = false;
      this.bloquear = false;

      this.form.id = 0;
      this.form.nit = null;
      this.form.name = null;
      this.form.ubication = null;
      this.form.arrival_date = null;
      this.form.departure_date = null;
      this.form.client_id = null;
      this.form.coin_id = null;
      this.form.details = [];

      this.dialog = false;
      this.dialog_cliente = false;
      this.habitaciones = [];
      this.todos_precios = [];
      this.todos_masajes = [];
      this.seleccionados = [];

      this.$validator.reset();
      this.$validator.reset();
    },

    buscar_habitacion(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          let objeto = new Object();
          objeto.inicio = this.form.arrival_date;
          objeto.fin = this.form.departure_date;
          objeto.servicios = this.servicios.length != 0 ? this.servicios : [];

          this.loading = true;
          this.$store.state.services.reservationService
            .buscar_habitaciones(objeto)
            .then((r) => {
              this.loading = false;

              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  return;
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  return;
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, "Mensaje");
                  }
                }
                return;
              }

              this.habitaciones = r.data.habitaciones;
              this.todos_precios = r.data.precios;
              this.todos_masajes = r.data.masajes;
            })
            .catch((r) => {});
        }
      });
    },

    reservar(item) {
      this.$swal({
        title: "Reservar",
        text: "¿Está seguro de realizar esta acción?",
        type: "info",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading_room = true;
          let objecto = new Object();
          objecto.price = null;

          this.seleccionados.forEach((element) => {
            if (element.id == item.id) {
              objecto.price = element.sf_price;
            }
          });

          if (!objecto.price) {
            this.$toastr.error(
              "El precio no fue seleccionado.",
              "Error"
            ); 
            this.loading_room = false;
            return 0;
          }

          objecto.room_id = item.id;
          objecto.ofert = null;

          if (this.form.details.length > 0) {
            this.form.details.forEach((element, index) => {
              if (element.room_id == item.id)
                this.$toastr.info(
                  "La reservación ya fue agregada a la lista.",
                  "Reservación"
                );
              else this.form.details.push(objecto);
            });
          } else {
            this.form.details.push(objecto);
          }

          this.form.coin_id = item.coin_id;

          this.bloquear = true;
          item.esconder = true;
          this.loading_room = false;
        }
      });
    },

    eliminar_reservacion(item) {
      this.$swal({
        title: "Eliminar reservación",
        text: "¿Está seguro de realizar esta acción?",
        type: "error",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading_room = true;

          this.form.details.forEach((element, index) => {
            if (element.room_id == item.id)
              this.form.details.splice(this.form.details.indexOf(index), 1);
          });

          this.bloquear = this.form.details.length == 0 ? false : true;
          item.esconder = false;
          this.loading_room = false;
        }
      });
    },

    promociones(item, index) {
      this.promocion = null;
      this.promociones = null;
      this.$store.state.services.reservationService
        .promocion(item)
        .then((r) => {
          this.data_promociones = r.data.data;
          this.dialog = true;
          this.index = index;
        })
        .catch((r) => {});
    },

    aplicar_promocion(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.form.details.forEach((element, index) => {
            if (this.promocion.id == element.room_id && element.ofert != null) {
              element.price =
                parseFloat(element.price) - parseFloat(this.promocion.sf_price);
              element.ofert = this.promocion.id;

              this.$toastr.success("La fue aplicada.", "Promoción");
            }
          });

          this.promocion = null;
          this.promociones = null;
          this.dialog = false;
          console.log(this.form.details);
        } else {
          this.promocion = null;
          this.promociones = null;
          this.dialog = false;
        }
      });
    },

    agregar() {
      this.loading = true;

      this.$store.state.services.clientService
        .index()
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, "Mensaje");
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, "Mensaje");
              }
            }
            this.loading = false;
            return;
          }

          this.clientes = r.data.data;
          this.dialog_cliente = true;
          this.loading = false;
        })
        .catch((r) => {
          this.loading = false;
        });
    },

    guardar_reservacion() {
      this.$swal({
        title: "Guardar Reservación",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.form.nit = this.form.client_id.nit;
          this.form.name = this.form.client_id.full_name;
          this.form.ubication =
            this.form.client_id.municipality.full_name +
            ", " +
            this.form.client_id.ubication;

          this.loading = true;
          this.$store.state.services.reservationService
            .store(this.form)
            .then((r) => {
              this.loading = false;

              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  return;
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  return;
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, "Mensaje");
                  }
                }
                return;
              }

              this.$toastr.success(r.data, "Mensaje");
              this.limpiar();
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    traer_precios(item) {
      this.loading = true;

      this.$store.state.services.reservationService
        .precios(item)
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, "Mensaje");
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, "Mensaje");
              }
            }
            this.loading = false;
            return;
          }

          this.loading = false;
          return r.data.data;
        })
        .catch((r) => {
          this.loading = false;
          return [];
        });
    },

    seleccionar_precio(item) {
      this.loading = true;
      if (this.seleccionados.length > 0) {
        this.seleccionados.forEach((element) => {
          if (element.id == item.id) {
            element.id = item.id;
            element.rooms_prices = item.rooms_prices;
            element.name = item.name;
            element.sf_price = item.sf_price;

            this.loading = false;
            return 0;
          }
        });

        this.seleccionados.push(item);
        this.loading = false;
      } else {
        this.seleccionados.push(item);
        this.loading = false;
      }
    },

    getServicios() {
      this.$store.state.services.typeServiceService
        .index()
        .then((r) => {
          this.listar_servicios = r.data.data;
        })
        .catch((r) => {});
    },
  },
};
</script>