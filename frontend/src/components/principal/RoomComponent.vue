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
              <v-col cols="12" md="2">
                <label>Número de servicio</label>
                <vue-number-input
                  v-model="form.number"
                  :min="1"
                  :max="999"
                  controls
                  placeholder="Número de servicio"
                  data-vv-scope="create"
                  data-vv-name="número de servicio"
                  v-validate="'required|max:100'"
                ></vue-number-input>
                <FormError
                  :attribute_name="'create.número de servicio'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="10">
                <v-text-field
                  counter
                  outlined
                  v-model="form.name"
                  type="text"
                  label="Nombre"
                  data-vv-scope="create"
                  data-vv-name="nombre"
                  v-validate="'required'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.nombre'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="2">
                <label>Número de adultos</label>
                <vue-number-input
                  v-model="form.number_adults"
                  :min="1"
                  :max="99"
                  controls
                  placeholder="Número de adultos"
                  data-vv-scope="create"
                  data-vv-name="número de adultos"
                  v-validate="'required'"
                ></vue-number-input>
                <FormError
                  :attribute_name="'create.número de adultos'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="2">
                <label>Número de niños</label>
                <vue-number-input
                  v-model="form.number_children"
                  :min="0"
                  :max="99"
                  controls
                  placeholder="Número de niños"
                  data-vv-scope="create"
                  data-vv-name="número de niños"
                  v-validate="'required'"
                ></vue-number-input>
                <FormError
                  :attribute_name="'create.número de niños'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="2">
                <label>Número de camas</label>
                <vue-number-input
                  v-model="form.amount_bed"
                  :min="1"
                  :max="99"
                  controls
                  placeholder="Número de camas"
                  data-vv-scope="create"
                  data-vv-name="número de camas"
                  v-validate="'required'"
                ></vue-number-input>
                <FormError
                  :attribute_name="'create.número de camas'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="6" class="text-center"> </v-col>
              <v-col cols="12" md="3">
                <v-autocomplete
                  v-model="form.type_bed_id"
                  :items="camas"
                  chips
                  label="Seleccionar tipo de cama"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="create"
                  data-vv-name="tipo de cama"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'create.tipo de cama'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="3">
                <v-autocomplete
                  v-model="form.type_room_id"
                  :items="habitaciones"
                  @input="getMasaje"
                  chips
                  label="Seleccionar tipo de espacio"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="full_name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="create"
                  data-vv-name="tipo de espacio"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'create.tipo de espacio'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="6">
                <v-autocomplete
                  v-model="form.massages"
                  :items="masajes"
                  v-if="mostrar_masaje && !editedIndex"
                  chips
                  label="Seleccionar masajes"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  multiple
                  v-validate="'required'"
                  data-vv-scope="create"
                  data-vv-name="masajes"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'create.masajes'"
                  :errors_form="errors"
                ></FormError>
              </v-col>

              <v-col cols="12" md="12" v-if="!editedIndex">
                <v-file-input
                  v-model="masiva_image"
                  color="deep-purple accent-4"
                  counter
                  accept="image/png, image/jpeg, image/jpg"
                  multiple
                  placeholder="Seleccionar fotografías"
                  outlined
                  :show-size="1000"
                  @change="cargaMasiva($event)"
                >
                  <template v-slot:selection="{ index, text }">
                    <v-chip
                      v-if="index < 2"
                      color="deep-white accent-4"
                      dark
                      label
                      small
                      >{{ text }}</v-chip
                    >

                    <span
                      v-else-if="index === 2"
                      class="overline grey--text text--darken-3 mx-2"
                      >+{{ masiva_image.length - 2 }} File(s)</span
                    >
                  </template>
                </v-file-input>
              </v-col>

              <v-col cols="12" md="2" v-if="!mostrar_masaje">
                <label>Número de mascotas</label>
                <vue-number-input
                  v-model="form.amount_pets"
                  :min="0"
                  :max="99"
                  controls
                  placeholder="Número de mascotas"
                  data-vv-scope="create"
                  data-vv-name="número de mascotas"
                  v-validate="'required'"
                ></vue-number-input>
                <FormError
                  :attribute_name="'create.número de mascotas'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="3" v-if="!mostrar_masaje">
                <v-autocomplete
                  v-model="form.coin_id"
                  :items="monedas"
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

              <v-col cols="12" md="7"></v-col>

              <v-row v-if="!editedIndex && !mostrar_masaje">
                <v-col cols="12" md="2">
                  <v-autocomplete
                    v-model="type_charge_id"
                    :items="precios"
                    chips
                    label="Seleccionar tipo de cobro"
                    outlined
                    :clearable="true"
                    :deletable-chips="true"
                    item-text="name"
                    item-value="id"
                    return-object
                    v-validate="'required'"
                    data-vv-scope="agregar_precio"
                    data-vv-name="tipo de cobro"
                  ></v-autocomplete>
                  <FormError
                    :attribute_name="'agregar_precio.tipo de cobro'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <v-col cols="12" md="2" class="text-center">
                  <label>Precio de costo</label>
                  <input
                    dark
                    placeholder="Precio"
                    class="nuevo-input"
                    v-model="price.price"
                    @input="price.price = formatear_numero($event)"
                    data-vv-name="precio"
                    v-validate="'required'"
                    data-vv-scope="agregar_precio"
                  />
                  <FormError
                    :attribute_name="'agregar_precio.precio'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <v-col cols="12" md="2" class="text-rigth">
                  <v-switch
                    v-model="price.web"
                    :label="`Precio para la WEB: ${
                      price.web ? 'SI' : 'NO'
                    }`"
                    data-vv-name="precio web"
                    v-validate="'required'"
                    data-vv-scope="agregar_precio"
                  ></v-switch>
                  <FormError
                    :attribute_name="'agregar_precio.precio web'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <v-col cols="12" md="1">
                  <v-btn
                    color="blue darken-1"
                    @click="agregar_precio('agregar_precio')"
                    >Guardar</v-btn
                  >
                </v-col>
                <v-col cols="12" md="5">
                  <v-simple-table dark>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-center">Tipo de Cobro</th>
                          <th class="text-center">Precio</th>
                          <th class="text-center">WEB</th>
                        </tr>
                      </thead>
                      <tbody v-if="form.prices.length > 0">
                        <tr v-for="(item, index) in form.prices" :key="index">
                          <td class="text-center">
                            {{ item.type_charge.name }}
                          </td>
                          <td class="text-center">{{ item.price }}</td>
                          <td class="text-center">
                            {{ item.web ? "SI" : "NO" }}
                          </td>
                          <td class="text-center">
                            <v-btn color="error" @click="quitar_price(item)"
                              >Eliminar</v-btn
                            >
                          </td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-col>
              </v-row>

              <v-col cols="12" md="12" class="text-center">
                <h3>Descripción del servicio</h3>
                <quill-editor
                  class="editor"
                  v-validate="'required|min:50'"
                  data-vv-scope="create"
                  data-vv-name="descripción"
                  v-model="form.description"
                />
                <FormError
                  :attribute_name="'create.descripción'"
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
            >Guardar</v-btn
          >
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
            <v-toolbar-title>Habitaciones</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
          </v-toolbar>
        </template>
        <template v-slot:item.description="{ item }">
          <br />
          <v-card color="#385F73" dark>
            <v-card-title class="headline">
              {{ item.number + " " + item.name }}
            </v-card-title>
            <hr />
            <v-card-subtitle>
              <ul>
                <li>Cantidad de Personas: {{ item.amount_people }}</li>
                <li>
                  Tipo de Cama: {{ item.amount_bed }} {{ item.type_bed.name }}
                </li>
                <li>Servicio: {{ item.type_room.name }}</li>
              </ul>
              <p v-html="item.description"></p>
            </v-card-subtitle>

            <v-card-actions>
              <h1>Precio {{ item.coin.symbol }} {{ item.price }}</h1>
            </v-card-actions>
          </v-card>
          <br />
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn
            v-if="item.type_service_id == 2"
            class="ma-2"
            color="primary lighten-2"
            small
          >
            Masajes
          </v-btn>
          <v-btn class="ma-2" color="default lighten-2" small> Precios </v-btn>
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

<style scoped>
.quill-editor,
.content {
  background-color: white;
  color: black;
}
</style>

<script>
import FormError from "../shared/FormError";

export default {
  name: "Room",
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      editedIndex: false,
      search: "",
      headers: [
        {
          text: "Número de servicio",
          align: "start",
          value: "number",
        },
        {
          text: "Servicio",
          align: "start",
          value: "type_service.name",
        },
        {
          text: "Nombre",
          align: "start",
          value: "name",
        },
        {
          text: "Descripción",
          align: "start",
          value: "description",
        },
        { text: "Opciones", value: "actions", sortable: false },
      ],
      footer: {
        showFirstLastPage: true,
        firstIcon: "mdi-arrow-collapse-left",
        lastIcon: "mdi-arrow-collapse-right",
        prevIcon: "mdi-minus",
        nextIcon: "mdi-plus",
      },
      desserts: [],
      monedas: [],
      camas: [],
      habitaciones: [],
      masiva_image: [],
      precios: [],
      masajes: [],
      form: {
        id: 0,
        number: null,
        name: null,
        amount_people: 0,
        amount_bed: 0,
        price: null,
        description: null,
        type_bed_id: null,
        type_room_id: null,
        coin_id: null,
        pictures: [],
        pets: false,
        prices: [],
        massages: [],
        number_adults: 0,
        number_children: 0,
        amount_pets: 0,
      },
      type_charge_id: null,
      price: {
        price: null,
        default: false,
        type_charge_id: null,
        web: false,
      },
    };
  },
  computed: {
    formTitle() {
      return !this.editedIndex ? "Agregar Servicio" : "Editar Servicio";
    },
    mostrar_masaje() {
      return this.form.type_room_id
        ? this.form.type_room_id.type_service_id != 1
          ? true
          : false
        : false;
    },
  },

  created() {
    this.initialize();
    this.getMoneda();
    this.getCama();
    this.getHabitacion();
  },

  methods: {
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
      this.dialog = false;

      this.form.id = 0;
      this.form.number = null;
      this.form.name = null;
      this.form.amount_people = 0;
      this.form.amount_bed = 0;
      this.form.price = null;
      this.form.description = null;
      this.form.type_bed_id = null;
      this.form.type_room_id = null;
      this.form.coin_id = null;
      this.form.pictures = [];
      this.form.prices = [];
      this.form.pets = false;
      this.form.massages = [];
      this.form.number_adults = 0;
      this.form.number_children = 0;
      this.form.amount_pets = 0;

      this.$validator.reset();
      this.$validator.reset();
    },

    initialize() {
      this.loading = true;

      this.$store.state.services.roomService
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

          this.desserts = r.data.data;
          this.close();
          this.loading = false;
        })
        .catch((r) => {
          this.loading = false;
        });
    },

    mapear(item) {
      this.form.id = item.id;
      this.form.number = item.number;
      this.form.name = item.name;
      this.form.amount_people = item.amount_people;
      this.form.amount_bed = item.amount_bed;
      this.form.price = item.price;
      this.form.description = item.description;
      this.form.type_bed_id = item.type_bed_id;
      this.form.type_room_id = item.type_room_id;
      this.form.coin_id = item.coin_id;
      this.form.pets = item.pets;
      this.form.pictures = [];
      this.form.prices = [];
      this.form.massages = [];
      this.masiva_image = [];
      this.form.number_adults = item.number_adults;
      this.form.number_children = item.number_children;
      this.form.amount_pets = item.amount_pets;

      this.editedIndex = true;
    },

    close() {
      this.limpiar();
    },

    validar_formulario(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result)
          this.editedIndex ? this.update(this.form) : this.store(this.form);
      });
    },

    destroy(data) {
      let title = !data.deleted_at ? "Desactivar" : "Activar";
      let type = !data.deleted_at ? "error" : "success";
      this.$swal({
        title: title,
        text: "¿Está seguro de realizar esta acción?",
        type: type,
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.roomService
            .destroy(data)
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
              this.initialize();
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    store(data) {
      if (this.form.prices.length == 0 && this.form.massages.length == 0) {
        this.$toastr.warning("Debe de agregar al menos un precio.", "Mensaje");
        return 0;
      }

      this.$swal({
        title: "Agregar",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.roomService
            .store(data)
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
              this.initialize();
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    update(data) {
      this.$swal({
        title: "Modificar",
        text: "¿Está seguro de realizar esta acción?",
        type: "warning",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.roomService
            .update(data)
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
              this.initialize();
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    //Carga masiva de fotografias
    cargaMasiva(e) {
      this.form.pictures = [];
      e.forEach((file) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => this.form.pictures.push({ photo: reader.result });
      });
    },

    getMoneda() {
      this.$store.state.services.coinService
        .index()
        .then((r) => {
          this.monedas = r.data.data;
        })
        .catch((r) => {});
    },

    getCama() {
      this.$store.state.services.typeBedService
        .index()
        .then((r) => {
          this.camas = r.data.data;
        })
        .catch((r) => {});
    },

    getHabitacion() {
      this.$store.state.services.typeRoomService
        .index()
        .then((r) => {
          this.habitaciones = r.data.data;
        })
        .catch((r) => {});
    },

    getMasaje(item) {
      this.masajes = [];
      this.precios = [];
      this.form.prices = [];
      this.$store.state.services.typeMessageService
        .index()
        .then((r) => {
          r.data.data.forEach((x) => {
            if (!x.deleted_at && item.type_service_id == x.type_service_id) {
              this.form.coin_id = x.coin;
              this.masajes.push({
                id: x.id,
                name: `${x.name} | Precio: ${x.coin.symbol} ${x.price} | Tiempo: ${x.time} min.`,
              });
            }
          });
        })
        .catch((r) => {});

      if(item.type_service_id == 1) {
        this.$store.state.services.typeChargeService
          .index()
          .then((r) => {
            r.data.data.forEach(x => {
              if(!x.deleted_at && item.type_service_id == x.type_service_id)
              this.precios.push(x)
            })
          })
          .catch((r) => {});
      }
    },

    agregar_precio(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          let objeto = new Object();
          objeto.price = this.price.price;
          objeto.default = this.price.default;
          objeto.type_charge_id = this.type_charge_id.id;
          objeto.type_charge = this.type_charge_id;
          objeto.web = this.price.web;

          this.form.prices.push(objeto);

          this.price.price = null;
          this.price.default = false;
          this.price.web = false;
          this.price.type_charge_id = null;
          this.type_charge_id = null;

          this.$toastr.info("Precio agregado", "Precio");

          this.$validator.reset();
          this.$validator.reset();
        }
      });
    },

    quitar_price(index) {
      this.form.prices.splice(this.form.prices.indexOf(index), 1);
    },
  },
};
</script>