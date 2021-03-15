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
              <v-col cols="12" md="3">
                <v-text-field
                  counter
                  outlined
                  v-model="form.nit"
                  type="text"
                  label="número de NIT"
                  data-vv-scope="create"
                  data-vv-name="número de NIT"
                  v-validate="'required|numeric|min:5|max:15'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.número de NIT'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="9">
                <v-text-field
                  counter
                  outlined
                  v-model="form.name"
                  type="text"
                  label="nombre del cliente"
                  data-vv-scope="create"
                  data-vv-name="nombre del cliente"
                  v-validate="'required|max:50'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.nombre del cliente'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  counter
                  outlined
                  v-model="form.email"
                  type="text"
                  label="Correo electrónico"
                  data-vv-scope="create"
                  data-vv-name="correo"
                  v-validate="'email|max:75'"
                  @input="form.email = $event.toLowerCase()"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.correo'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="4">
                <v-switch
                  v-model="form.business"
                  :label="`Es empresa: ${form.business ? 'SI' : 'NO'}`"
                  data-vv-name="es empresa"
                  v-validate="'required'"
                  data-vv-scope="create"
                ></v-switch>
                <FormError
                  :attribute_name="'create.es empresa'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="5"></v-col>
              <v-col cols="12" md="4">
                <v-autocomplete
                  v-model="form.municipality_id"
                  :items="municipios"
                  chips
                  label="Seleccionar departamento y municipio"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="full_name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="create"
                  data-vv-name="departamento y municipio"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'create.departamento y municipio'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="4">
                <v-text-field
                  counter
                  outlined
                  v-model="form.ubication"
                  type="text"
                  label="dirección exacta"
                  data-vv-scope="create"
                  data-vv-name="dirección exacta"
                  v-validate="'max:100'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.dirección exacta'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
            </v-row>
            <v-row v-if="!editedIndex">
              <v-col cols="12" md="4">
                <vue-phone-number-input
                  v-model="number"
                  default-country-code="GT"
                  size="lg"
                  :dark="true"
                  :translations="translations"
                  show-code-on-list
                  @update="validar_numero($event)"
                  v-validate="'required'"
                  data-vv-scope="agregar_telefono"
                  data-vv-name="número de teléfono"
                  :no-flags="true"
                  required
                />
                <FormError
                  :attribute_name="'agregar_telefono.número de teléfono'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="2">
                <v-btn
                  v-if="esconder_boton"
                  color="blue darken-1"
                  @click="agregar_telefono('agregar_telefono')"
                  >Guardar</v-btn
                >
              </v-col>
              <v-col cols="12" md="6">
                <v-simple-table dark>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-center">País</th>
                        <th class="text-center">Código de Área</th>
                        <th class="text-center">Número de Teléfono</th>
                        <th class="text-center">Link Llamada</th>
                        <th class="text-center">Opción</th>
                      </tr>
                    </thead>
                    <tbody v-if="form.phones.length > 0">
                      <tr v-for="(item, index) in form.phones" :key="index">
                        <td class="text-center">{{ item.country }}</td>
                        <td class="text-center">{{ item.area_code }}</td>
                        <td class="text-center">{{ item.number }}</td>
                        <td class="text-center">{{ item.url }}</td>
                        <td class="text-center">
                          <v-btn color="error" @click="delete_telefono(item)"
                            >Eliminar</v-btn
                          >
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="limpiar">Cancelar</v-btn>
          <v-btn
            color="primary darken-1"
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
            <v-toolbar-title>Clientes</v-toolbar-title>
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
        <template v-slot:item.phones="{ item }">
          <ul v-for="(item, index) in item.phones" v-bind:key="index">
            <li>
              <a :href="item.url"
                >{{ item.country }} {{ item.area_code + " " + item.number }}</a
              >
            </li>
          </ul>
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
          <v-btn
            class="ma-2"
            text
            icon
            color="info lighten-2"
            @click="mapear_telefonos(item)"
          >
            <v-icon class="material-icons">mdi-phone</v-icon>
          </v-btn>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>
    </v-col>

    <v-dialog
      v-model="dialog"
      persistent
      color="primary"
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">{{ title }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row class="text-center">
              <v-col cols="12" md="4">
                <vue-phone-number-input
                  v-model="number"
                  default-country-code="GT"
                  size="lg"
                  :dark="true"
                  :translations="translations"
                  show-code-on-list
                  @update="validar_numero($event)"
                  :no-flags="true"
                  required
                />
              </v-col>
              <v-col cols="12" md="2">
                <v-btn
                  v-if="esconder_boton"
                  color="blue darken-1"
                  @click="store_phone(form_telefono)"
                  >Guardar</v-btn
                >
              </v-col>

              <v-col cols="12">
                <v-simple-table dark>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-center">País</th>
                        <th class="text-center">Código de Área</th>
                        <th class="text-center">Número de Teléfono</th>
                        <th class="text-center">Link Llamada</th>
                        <th class="text-center">Opción</th>
                      </tr>
                    </thead>
                    <tbody v-if="telefonos_mostrar.length > 0">
                      <tr
                        v-for="(item, index) in telefonos_mostrar"
                        :key="index"
                      >
                        <td class="text-center">{{ item.country }}</td>
                        <td class="text-center">{{ item.area_code }}</td>
                        <td class="text-center">{{ item.number }}</td>
                        <td class="text-center">{{ item.url }}</td>
                        <td class="text-center">
                          <v-btn color="info" small @click="destroy_phone(item)"
                            >Eliminar</v-btn
                          >
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" @click="cerrar_dialog">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import FormError from "../shared/FormError";

export default {
  name: "Client",
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      editedIndex: false,
      esconder_boton: false,
      number: null,
      search: "",
      title: "",
      headers: [
        {
          text: "NIT",
          align: "start",
          value: "nit",
        },
        {
          text: "Nombre",
          align: "start",
          value: "name",
        },
        {
          text: "Nombre",
          align: "start",
          value: "email",
        },
        {
          text: "Departamento y Municipio",
          align: "start",
          value: "municipality.full_name",
        },
        {
          text: "Dirección",
          align: "start",
          value: "ubication",
        },
        {
          text: "Teléfonos",
          align: "start",
          value: "phones",
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
      municipios: [],
      telefonos: [],
      form: {
        id: 0,
        nit: null,
        name: null,
        email: null,
        ubication: null,
        municipality_id: null,
        phones: [],
        business: false,
      },
      form_telefono: {
        id: 0,
        number: null,
        area_code: null,
        country: null,
        url: null,
      },
      translations: {
        countrySelectorLabel: "Código de país",
        countrySelectorError: "Elige un país",
        phoneNumberLabel: "Número de teléfono",
        example: "Ejemplo :",
      },
      temporal: null,
    };
  },
  computed: {
    formTitle() {
      return !this.editedIndex ? "Agregar Cliente" : "Editar Cliente";
    },
    telefonos_mostrar() {
      return this.telefonos;
    },
  },

  created() {
    this.initialize();
    this.getMunicipios();
  },

  methods: {
    validar_numero(e) {
      this.form_telefono.number = e.isValid ? e.phoneNumber : null;
      this.form_telefono.area_code = e.isValid
        ? "+" + e.countryCallingCode
        : null;
      this.form_telefono.country = e.isValid ? e.countryCode : null;
      this.form_telefono.url = e.isValid ? e.uri : null;
      this.esconder_boton = e.isValid ? true : false;
    },

    limpiar() {
      this.editedIndex = false;

      this.form.id = 0;
      this.form.nit = null;
      this.form.name = null;
      this.form.business = null;
      this.form.email = null;
      this.form.ubication = null;
      this.form.municipality_id = null;
      this.form.phones = [];

      this.$validator.reset();
      this.$validator.reset();
    },

    initialize() {
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

          this.desserts = r.data.data;
          this.limpiar();
          this.loading = false;
        })
        .catch((r) => {
          this.loading = false;
        });
    },

    mapear(item) {
      this.form.id = item.id;
      this.form.nit = item.nit;
      this.form.name = item.name;
      this.form.business = item.business;
      this.form.email = item.email;
      this.form.ubication = item.ubication;
      this.form.municipality_id = item.municipality;
      this.form.phones = [];

      this.editedIndex = true;
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
          this.$store.state.services.clientService
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
          this.limpiar();
        }
      });
    },

    store(data) {
      this.$swal({
        title: "Agregar",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.clientService
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
          this.limpiar();
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
          this.$store.state.services.clientService
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
          this.limpiar();
        }
      });
    },

    agregar_telefono(scope) {
      if (!this.esconder_boton) {
        this.$toastr.info("El número de teléfono no es válido", "Teléfono");
      }
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          let object = new Object();
          object.number = this.form_telefono.number;
          object.area_code = this.form_telefono.area_code;
          object.country = this.form_telefono.country;
          object.url = this.form_telefono.url;

          this.form.phones.push(object);

          this.form_telefono.number = null;
          this.form_telefono.area_code = null;
          this.form_telefono.country = null;
          this.form_telefono.url = null;

          this.esconder_boton = false;
          this.number = null;

          this.$validator.reset();
          this.$validator.reset();
        }
      });
    },

    delete_telefono(index) {
      this.form.phones.splice(this.form.phones.indexOf(index), 1);
    },

    getMunicipios() {
      this.$store.state.services.municipalityService
        .index()
        .then((r) => {
          this.municipios = r.data.data;
        })
        .catch((r) => {});
    },

    mapear_telefonos(item) {
      this.loading = true;
      this.title = "Administrar números de teléfono de " + item.full_name;

      this.$store.state.services.clientPhoneService
        .show(item)
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

          this.telefonos = r.data.data.phones;
          this.form_telefono.id = item.id;
          this.esconder_boton = false;
          this.number = null;
          this.dialog = true;
          this.temporal = item;
          this.loading = false;
        })
        .catch((r) => {
          this.loading = false;
          this.dialog = false;
        });
    },

    store_phone(data) {
      this.$swal({
        title: "Agregar Número de Teléfono",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.clientPhoneService
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
              this.mapear_telefonos(this.temporal);
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.dialog = false;
        }
      });
    },

    destroy_phone(data) {
      this.$swal({
        title: "Eliminar Número de Teléfono",
        text: "¿Está seguro de realizar esta acción?",
        type: "error",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.clientPhoneService
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
              this.mapear_telefonos(this.temporal);
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.dialog = false;
        }
      });
    },

    cerrar_dialog() {
      this.dialog = false;
      this.initialize();
    },
  },
};
</script>

