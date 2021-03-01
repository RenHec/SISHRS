<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

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
            <v-toolbar-title>Usuarios</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
            <v-spacer></v-spacer>

            <v-dialog
              v-model="dialog"
              color="primary"
              fullscreen
              hide-overlay
              persistent
              transition="dialog-bottom-transition"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  :loading="dialog"
                  :disabled="dialog"
                  @click="dialog = true"
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                  >Agregar</v-btn
                >
              </template>
              <v-card>
                <v-overlay :value="loading">
                  <v-progress-circular
                    indeterminate
                    size="64"
                  ></v-progress-circular>
                </v-overlay>
                <v-card-title>
                  <v-toolbar dark color="primary">
                    <v-btn icon @click="close">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                      <v-btn v-if="esconder_boton" text @click="validar_formulario('crear_usuario')"
                        >Guardar</v-btn
                      >
                    </v-toolbar-items>
                  </v-toolbar>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="12" md="4">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.cui"
                          type="text"
                          label="número de CUI"
                          data-vv-scope="crear_usuario"
                          data-vv-name="número de CUI"
                          v-validate="'required|digits:13'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.número de CUI'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.first_name"
                          type="text"
                          label="primer nombre"
                          data-vv-scope="crear_usuario"
                          data-vv-name="primer nombre"
                          v-validate="'required|max:50'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.primer nombre'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.second_name"
                          type="text"
                          label="segundo nombre"
                          data-vv-scope="crear_usuario"
                          data-vv-name="segundo nombre"
                          v-validate="'max:50'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.segundo nombre'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.surname"
                          type="text"
                          label="primer apellido"
                          data-vv-scope="crear_usuario"
                          data-vv-name="primer apellido"
                          v-validate="'required|max:50'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.primer apellido'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.second_surname"
                          type="text"
                          label="segundo apellido"
                          data-vv-scope="crear_usuario"
                          data-vv-name="segundo apellido"
                          v-validate="'max:50'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.segundo apellido'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.married_name"
                          type="text"
                          label="apellido de casad@"
                          data-vv-scope="crear_usuario"
                          data-vv-name="apellido de casad@"
                          v-validate="'max:50'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.apellido de casad@'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="6">
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
                          data-vv-scope="crear_usuario"
                          data-vv-name="departamento y municipio"
                        ></v-autocomplete>
                        <FormError
                          :attribute_name="'crear_usuario.departamento y municipio'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.ubication"
                          type="text"
                          label="dirección exacta"
                          data-vv-scope="crear_usuario"
                          data-vv-name="dirección exacta"
                          v-validate="'max:100'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.dirección exacta'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
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
                          data-vv-scope="crear_usuario"
                          data-vv-name="número de teléfono"
                          :no-flags="true"
                          required
                        />
                        <FormError
                          :attribute_name="'crear_usuario.número de teléfono'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="4">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.email"
                          type="text"
                          label="Correo electrónico"
                          data-vv-scope="crear_usuario"
                          data-vv-name="correo"
                          v-validate="'required|email|max:75'"
                          @input="form.email = $event.toLowerCase()"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.correo'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="4" v-if="form.id == 0">
                        <v-text-field
                          counter
                          outlined
                          v-model="form.password"
                          type="password"
                          label="Ingresar la contraseña"
                          data-vv-scope="crear_usuario"
                          data-vv-name="contraseña"
                          v-validate="'required|min:6'"
                        ></v-text-field>
                        <FormError
                          :attribute_name="'crear_usuario.contraseña'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="12" v-if="!editedIndex">
                        <v-select
                          v-model="form.roles"
                          :items="roles"
                          chips
                          label="Seleccione uno o más roles"
                          multiple
                          outlined
                          :clearable="true"
                          :deletable-chips="true"
                          item-text="name"
                          item-value="id"
                          return-object
                          v-validate="'required'"
                          data-vv-scope="crear_usuario"
                          data-vv-name="roles"
                        ></v-select>
                        <FormError
                          :attribute_name="'crear_usuario.roles'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="12">
                        <v-textarea
                          counter
                          outlined
                          auto-grow
                          rows="3"
                          row-height="15"
                          v-model="form.observation"
                          type="text"
                          label="Obervaciones"
                          data-vv-name="observaciones"
                          v-validate="'max:250'"
                          @input="form.observation = $event.toUpperCase()"
                        ></v-textarea>
                        <FormError
                          :attribute_name="'observaciones'"
                          :errors_form="errors"
                        ></FormError>
                      </v-col>
                      <v-col cols="12" md="12">
                        <v-file-input
                          outlined
                          v-model="temp"
                          @change="cargarImagen"
                          accept="image/png, image/jpeg, image/jpg"
                          placeholder="Imagen"
                          prepend-icon="mdi-camera"
                        ></v-file-input>
                      </v-col>
                      <v-col cols="12" md="6">
                        <cropper
                          class="cropper"
                          image-restriction="area"
                          :src="imagen_upload"
                          ref="cropper"
                          stencil-component="circle-stencil"
                        ></cropper>
                        <div class="text-center" v-if="imagen_upload">
                          <v-btn
                            class="mx-2"
                            fab
                            dark
                            color="indigo"
                            @click="crop"
                          >
                            <v-icon class="material-icons"
                              >format_shapes</v-icon
                            >
                          </v-btn>
                        </div>
                      </v-col>
                      <v-col cols="12" md="6" class="text-center">
                        <v-list-item-avatar
                          style="height: 80%; min-width: 80%; width: 80%"
                        >
                          <img :src="form.photo" alt width="80%" height="80%" />
                        </v-list-item-avatar>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.name="{ item }">
          <div class="text-center">
            <v-list-item-avatar style="height: 40%; min-width: 40%; width: 40%">
              <img :src="item.picture" />
            </v-list-item-avatar>
            <br />
            {{ item.cui }}
            <br />
            {{ item.full_name }}
          </div>
        </template>

        <template v-slot:item.users_rols="{ item }">
          <template v-for="x in item.rols">
            <span v-bind:key="x.rol.name + x.id" v-text="x.rol.name"></span>
            <br v-bind:key="x.id" />
          </template>
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
            color="orange lighten-2"
            v-if="!item.deleted_at"
            @click="cambiar_password(item)"
          >
            <v-icon>mdi-key</v-icon>
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
            @click="mapear_roles(item)"
          >
            <v-icon class="material-icons">library_books</v-icon>
          </v-btn>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>

      <v-dialog v-model="dialog_rol" color="primary">
        <v-card height="400px">
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
          <v-card-title>
            <span class="headline">Agregar rol</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="12" v-if="!editedIndex">
                  <v-select
                    v-model="form_rol.roles"
                    :items="roles"
                    chips
                    label="Seleccione uno o más roles"
                    multiple
                    outlined
                    :clearable="true"
                    :deletable-chips="true"
                    item-text="name"
                    item-value="id"
                    return-object
                    v-validate="'required'"
                    data-vv-scope="crear_rol"
                    data-vv-name="roles"
                  ></v-select>
                  <FormError
                    :attribute_name="'crear_rol.roles'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialog_rol = false"
              >Cancelar</v-btn
            >
            <v-btn
              color="blue darken-1"
              text
              @click="validar_formulario_rol('crear_rol')"
              >Guardar</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog
        v-model="dialog_password"
        width="35%"
        persistent
        color="primary"
      >
        <v-card>
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
          <v-card-title>
            <span class="headline">Cambiar contraseña</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="12">
                  <v-text-field
                    counter
                    outlined
                    v-model="form.password"
                    type="password"
                    label="Ingresar la contraseña"
                    data-vv-scope="crear_password"
                    data-vv-name="contraseña"
                    v-validate="'required|min:6'"
                  ></v-text-field>
                  <FormError
                    :attribute_name="'crear_password.contraseña'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" @click="dialog_password = false"
              >Cancelar</v-btn
            >
            <v-btn
              color="blue darken-1"
              @click="validar_formulario_password('crear_password')"
              >Guardar</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-col>
  </v-row>
</template>

<style scoped>
.preview {
  border: dashed 1px rgba(170, 29, 29, 0.25);
}
.table {
  color: white;
}
</style>

<script>
import FormError from "../shared/FormError";

export default {
  name: "Usuario",
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      dialog_password: false,
      dialog_rol: false,
      esconder_boton: false,
      editedIndex: false,
      search: "",
      imagen_upload: null,
      number: null,
      temp: null,
      headers: [
        {
          text: "CUI",
          align: "start",
          value: "cui",
        },
        {
          text: "Nombre",
          align: "start",
          value: "name",
        },
        {
          text: "E-mail",
          align: "start",
          value: "email",
        },
        {
          text: "Teléfono",
          align: "start",
          value: "phone",
        },
        {
          text: "Rol",
          align: "start",
          value: "users_rols",
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
      roles: [],
      form: {
        id: 0,
        cui: null,
        first_name: null,
        second_name: null,
        surname: null,
        second_surname: null,
        married_name: null,
        photo: null,
        email: null,
        observation: null,
        ubication: null,
        phone: null,
        municipality_id: null,
        roles: [],
        password: null,
      },
      form_rol: {
        id: null,
        roles: [],
      },
      translations: {
        countrySelectorLabel: "Código de país",
        countrySelectorError: "Elige un país",
        phoneNumberLabel: "Número de teléfono",
        example: "Ejemplo :",
      },
    };
  },
  computed: {
    formTitle() {
      return !this.editedIndex ? "Agregar usuario" : "Administrar usuario";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  created() {
    this.initialize();
    this.getRoles();
    this.getMunicipios();
  },

  methods: {
    validar_numero(e) {
      this.form.phone = e.isValid ? e.phoneNumber : null;
      this.esconder_boton = this.form.phone ? true : false;
    },

    limpiar() {
      this.editedIndex = false;
      this.imagen_upload = null;
      this.temp = null;
      this.number = null;
      this.esconder_boton = false;

      this.form.id = 0;
      this.form.cui = null;
      this.form.first_name = null;
      this.form.second_name = null;
      this.form.surname = null;
      this.form.second_surname = null;
      this.form.married_name = null;
      this.form.photo = null;
      this.form.email = null;
      this.form.observation = null;
      this.form.ubication = null;
      this.form.phone = null;
      this.form.municipality_id = null;
      this.form.roles = [];
      this.form.password = null;

      this.$validator.reset();
      this.$validator.reset();
    },

    initialize() {
      this.loading = true;

      this.$store.state.services.userService
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
      this.form.cui = item.cui;
      this.form.first_name = item.first_name;
      this.form.second_name = item.second_name;
      this.form.surname = item.surname;
      this.form.second_surname = item.second_surname;
      this.form.married_name = item.married_name;
      this.form.email = item.email;
      this.form.observation = item.observation;
      this.form.ubication = item.ubication;
      this.form.phone = item.phone;
      this.form.municipality_id = item.municipality;
      this.imagen_upload = item.picture;

      this.number = item.phone;

      this.editedIndex = true;
      this.dialog = true;
    },

    cambiar_password(item) {
      this.loading = true;
      this.form.id = item.id;
      this.dialog_password = true;
      this.loading = false;
    },

    validar_formulario_password(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.$swal({
            title: "Cambiar contraseña",
            text: "¿Está seguro de realizar esta acción?",
            type: "warning",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.form.password = window.btoa(this.form.password);
              this.loading = true;
              this.$store.state.services.userService
                .reset(this.form)
                .then((r) => {
                  this.loading = false;

                  if (r.response) {
                    if (r.response.data.code === 404) {
                      this.$toastr.warning(
                        r.response.data.error,
                        "Advertencia"
                      );
                      return;
                    } else if (r.response.data.code === 423) {
                      this.$toastr.warning(
                        r.response.data.error,
                        "Advertencia"
                      );
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
                  this.dialog_password = false;
                })
                .catch((r) => {
                  this.loading = false;
                });
            } else {
              this.close();
            }
          });
        }
      });
    },

    close() {
      this.limpiar();
      this.dialog = false;
      this.dialog_rol = false;
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
          this.$store.state.services.userService
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
      this.$swal({
        title: "Agregar",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.form.password = window.btoa(this.form.password);
          this.loading = true;
          this.$store.state.services.userService
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
          this.$store.state.services.userService
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

    //necesarios
    cargarImagen(e) {
      this.imagen_upload = null;
      e !== "undefined" ? (this.imagen_upload = URL.createObjectURL(e)) : null;
      this.form.photo = null;
    },

    crop() {
      this.form.photo = null;
      const { coordinates, canvas } = this.$refs.cropper.getResult();
      this.form.photo = canvas.toDataURL();
    },

    getRoles() {
      this.$store.state.services.rolService
        .index()
        .then((r) => {
          this.roles = r.data.data;
        })
        .catch((r) => {});
    },

    getMunicipios() {
      this.$store.state.services.municipalityService
        .index()
        .then((r) => {
          this.municipios = r.data.data;
        })
        .catch((r) => {});
    },

    limipiar_form_rol() {
      this.form_rol.id = 0;
      this.form_rol.roles = [];

      this.$validator.reset();
      this.$validator.reset();
    },

    mapear_roles(item) {
      this.limipiar_form_rol();
      this.form_rol.id = item.id;

      item.rols.forEach((element) => {
        this.form_rol.roles.push(element.rol);
      });

      this.dialog_rol = true;
    },

    validar_formulario_rol(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.$swal({
            title: "Agregar",
            text: "¿Está seguro de realizar esta acción?",
            type: "success",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.loading = true;
              this.$store.state.services.userRolService
                .store(this.form_rol)
                .then((r) => {
                  this.loading = false;

                  if (r.response) {
                    if (r.response.data.code === 404) {
                      this.$toastr.warning(
                        r.response.data.error,
                        "Advertencia"
                      );
                      return;
                    } else if (r.response.data.code === 423) {
                      this.$toastr.warning(
                        r.response.data.error,
                        "Advertencia"
                      );
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
        }
      });
    },
  },
};
</script>

