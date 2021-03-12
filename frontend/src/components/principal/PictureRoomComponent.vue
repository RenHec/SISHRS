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
          <span class="headline">Agregar fotografías del servicio</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" md="6">
                <v-autocomplete
                  @input="initialize"
                  v-model="habitacion"
                  :items="habitaciones"
                  chips
                  label="Seleccionar el servicio"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="create"
                  data-vv-name="servicio"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'create.servicio'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="6">
                <v-file-input
                  v-model="masiva_image"
                  color="deep-purple accent-4"
                  counter
                  accept="image/png, image/jpeg, image/jpg"
                  multiple
                  placeholder="Seleccionar fotografías del servicio"
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
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="habitacion = null"
            >Cancelar</v-btn
          >
          <v-btn
            color="blue darken-1"
            text
            v-if="form.id > 0"
            @click="validar_formulario('create')"
            >Guardar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-col>

    <v-col cols="12" md="12">
      <v-card color="info">
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">Fotografías</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" md="12" class="text-center">
                <v-btn x-large dark color="success" @click="update_masive">Aplicar cambios</v-btn>
              </v-col>
              <v-col
                class="text-center"
                cols="12"
                md="4"
                v-for="(item, index) in desserts"
                :key="index"
              >
                <v-card color="#fff" class="d-inline-block mx-auto">
                  <v-container>
                    <v-row justify="space-between">
                      <v-col cols="auto">
                        <v-img
                          style="width: 225px; height: 225px"
                          :src="item.picture"
                          :alt="'habitacion' + item.id"
                        ></v-img>
                      </v-col>

                      <v-col cols="auto" class="text-center pl-0">
                        <v-row
                          class="flex-column ma-0 fill-height"
                          justify="center"
                        >
                          <v-col class="px-0">
                            <v-btn
                              color="primary"
                              @click="up_image(item)"
                              icon
                            >
                              <v-icon>mdi-thumb-up</v-icon>
                            </v-btn>
                          </v-col>

                          <v-col class="px-0">
                            <v-btn color="error" @click="destroy(item)" icon>
                              <v-icon>mdi-delete</v-icon>
                            </v-btn>
                          </v-col>

                          <v-col class="px-0">
                            <v-btn
                              :color="item.view ? 'success' : 'blue-grey'"
                              @click="image_active(item)"
                              icon
                            >
                              <v-icon>mdi-eye</v-icon>
                            </v-btn>
                          </v-col>

                          <v-col class="px-0">
                            <v-btn
                              v-if="item.position == 1"
                              color="warning"
                              @click="down_image(item)"
                              icon
                            >
                              <v-icon>mdi-thumb-down</v-icon>
                            </v-btn>
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                    <v-row justify="center">
                      <v-col cols="12" md="8">
                        <vue-number-input
                          v-model="item.position"
                          :min="1"
                          :max="100"
                          inline
                          controls
                          @change="change_order($event, index)"
                        ></vue-number-input>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
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
  name: "TypeRoom",
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      habitacion: null,
      desserts: [],
      habitaciones: [],
      masiva_image: [],
      form: {
        id: 0,
        pictures: [],
      },
    };
  },

  created() {
    this.getHabitacion();
  },

  methods: {
    limpiar() {
      this.form.pictures = [];

      this.$validator.reset();
      this.$validator.reset();
    },

    initialize(item) {
      if (item) {
        this.loading = true;
        this.form.id = item.id;

        this.$store.state.services.pictureRoomService
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

            this.desserts = r.data.data;
            this.loading = false;
          })
          .catch((r) => {
            this.loading = false;
          });
      } else {
        this.desserts = [];
        this.form.id = 0;
        this.limpiar()
      }
    },

    validar_formulario(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) this.update(this.form);
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
          this.$store.state.services.pictureRoomService
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
              this.initialize(this.habitacion);
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
          this.$store.state.services.pictureRoomService
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
              this.initialize(this.habitacion);
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    update_masive() {
      let data = new Object();
      data.pictures = this.desserts;
      this.$swal({
        title: "Actualizar las posiciones",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.pictureRoomService.masive(data)
            .then((r) => {
              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  this.loading = false;
                  return;
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  this.loading = false;
                  return;
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, "Mensaje");
                  }
                }
                this.loading = false;
                return;
              }

              this.$toastr.success(r.data, "Mensaje");
              this.initialize(this.habitacion);
              this.loading = false;
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    up_image(data) {
      this.$swal({
        title: "Subir",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.pictureRoomService.up(data)
            .then((r) => {
              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  this.loading = false;
                  return;
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  this.loading = false;
                  return;
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, "Mensaje");
                  }
                }
                this.loading = false;
                return;
              }

              this.$toastr.success(r.data, "Mensaje");
              this.initialize(this.habitacion);
              this.loading = false;
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },

    image_active(item) {
      this.loading = true;

      this.$store.state.services.pictureRoomService.view(item)
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 404) {
              this.$toastr.warning(r.response.data.error, "Advertencia");
              this.loading = false;
              return;
            } else if (r.response.data.code === 423) {
              this.$toastr.warning(r.response.data.error, "Advertencia");
              this.loading = false;
              return;
            } else {
              for (let value of Object.values(r.response.data)) {
                this.$toastr.error(value, "Mensaje");
              }
            }
            this.loading = false;
            return;
          }

          this.$toastr.success(r.data, "Mensaje");
          this.initialize(this.habitacion);
          this.loading = false;
        })
        .catch((r) => {
          this.loading = false;
        });
    },

    down_image(data) {
      this.$swal({
        title: "Bajar",
        text: "¿Está seguro de realizar esta acción?",
        type: "success",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.$store.state.services.pictureRoomService.down(data)
            .then((r) => {
              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  this.loading = false;
                  return;
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, "Advertencia");
                  this.loading = false;
                  return;
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, "Mensaje");
                  }
                }
                this.loading = false;
                return;
              }

              this.$toastr.success(r.data, "Mensaje");
              this.initialize(this.habitacion);
              this.loading = false;
            })
            .catch((r) => {
              this.loading = false;
            });
        } else {
          this.close();
        }
      });
    },    
    
    change_order(num, index) {
      this.desserts[index].position = num;
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

    getHabitacion() {
      this.$store.state.services.roomService
        .index()
        .then((r) => {
          this.habitaciones = r.data.data;
        })
        .catch((r) => {});
    },
  },
};
</script>