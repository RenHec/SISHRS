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
              <v-col cols="12" md="3">
                <v-text-field
                  counter
                  outlined
                  v-model="form.symbol"
                  type="text"
                  label="Moneda"
                  data-vv-scope="create"
                  data-vv-name="moneda"
                  v-validate="'required|max:5|min:1'"
                  @input="form.symbol = $event.toUpperCase()"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.moneda'"
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
              <v-col cols="12" md="3" class="text-center">
                <label>Cambio</label>
                <input
                  dark
                  placeholder="Cambio"
                  class="nuevo-input"
                  v-model="form.change"
                  data-vv-name="cambio"
                  v-validate="'required|decimal:2'"
                  data-vv-scope="create"
                />
                <FormError
                  :attribute_name="'create.cambio'"
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
            <v-toolbar-title>Monedas</v-toolbar-title>
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
import FormError from "../shared/FormError";

export default {
  name: "Coin",
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
          text: "Moneda",
          align: "start",
          value: "symbol",
        },
        {
          text: "Nombre",
          align: "start",
          value: "name",
        },
        {
          text: "Cambio",
          align: "start",
          value: "change",
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
      form: {
        id: 0,
        symbol: null,
        name: null,
        change: null
      },
    };
  },
  computed: {
    formTitle() {
      return !this.editedIndex ? "Agregar Moneda" : "Editar Moneda";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    limpiar() {
      this.editedIndex = false;

      this.form.id = 0;
      this.form.symbol = null;
      this.form.name = null;
      this.form.change = null;

      this.$validator.reset();
      this.$validator.reset();
    },

    initialize() {
      this.loading = true;

      this.$store.state.services.coinService
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
      this.form.symbol = item.symbol;
      this.form.name = item.name;
      this.form.change = item.change;

      this.editedIndex = true;
      this.dialog = true;
    },

    close() {
      this.limpiar();
      this.dialog = false;
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
          this.$store.state.services.coinService
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
          this.loading = true;
          this.$store.state.services.coinService
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
          this.$store.state.services.coinService
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
  },
};
</script>

