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
              <v-col cols="12" md="12">
                <v-text-field
                  counter
                  outlined
                  v-model="form.name"
                  type="text"
                  label="Nombre"
                  data-vv-scope="create"
                  data-vv-name="nombre"
                  v-validate="'required|max:70|min:3'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.nombre'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="6">
                <v-row>
                  <v-col cols="12" md="6">
                    <v-autocomplete
                      v-model="form.category_id"
                      :items="categories"
                      @input="getSubCategory"
                      chips
                      label="Seleccionar categoría"
                      outlined
                      :clearable="true"
                      :deletable-chips="true"
                      item-text="name"
                      item-value="id"
                      return-object
                      v-validate="'required'"
                      data-vv-scope="create"
                      data-vv-name="categoría"
                    ></v-autocomplete>
                    <FormError
                      :attribute_name="'create.categoría'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-autocomplete
                      v-model="form.sub_category_id"
                      :items="sub_categories"
                      :loading="loading_sub"
                      chips
                      label="Seleccionar sub categoría"
                      outlined
                      :clearable="true"
                      :deletable-chips="true"
                      item-text="name"
                      item-value="id"
                      return-object
                      v-validate="'required'"
                      data-vv-scope="create"
                      data-vv-name="sub categoría"
                    ></v-autocomplete>
                    <FormError
                      :attribute_name="'create.sub categoría'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                  <v-col cols="12" md="6">
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
                  <v-col
                    cols="12"
                    md="6"
                    class="text-center"
                    v-if="!this.editedIndex"
                  >
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
                  <v-col cols="12" md="6" v-if="!this.editedIndex">
                    <label>Descuento %</label>
                    <vue-number-input
                      v-model="form.discount"
                      :min="0"
                      :max="100"
                      controls
                      placeholder="Descuento"
                      data-vv-scope="create"
                      data-vv-name="descuento"
                      v-validate="'required'"
                    ></vue-number-input>
                    <FormError
                      :attribute_name="'create.descuento'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                  <v-col cols="12" md="6">
                    <label>Notificar cuando falten</label>
                    <vue-number-input
                      v-model="form.notify"
                      :min="1"
                      :max="999"
                      controls
                      placeholder="Notificar"
                      data-vv-scope="create"
                      data-vv-name="descuento"
                      v-validate="'required'"
                    ></vue-number-input>
                    <FormError
                      :attribute_name="'create.descuento'"
                      :errors_form="errors"
                    ></FormError>
                  </v-col>
                  <v-col cols="12" md="12" v-if="!this.editedIndex">
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
                      ref="fileupload"
                    >
                      <template v-slot:selection="{ index, text }">
                        <v-chip
                          v-if="index < 2"
                          color="deep-white accent-4"
                          dark
                          label
                          small
                        >
                          {{ text }}
                        </v-chip>

                        <span
                          v-else-if="index === 2"
                          class="overline grey--text text--darken-3 mx-2"
                        >
                          +{{ masiva_image.length - 2 }} File(s)
                        </span>
                      </template>
                    </v-file-input>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" md="6">
                <v-textarea
                  counter
                  outlined
                  auto-grow
                  rows="20"
                  row-height="15"
                  v-model="form.description"
                  type="text"
                  label="Descripción"
                  data-vv-scope="create"
                  data-vv-name="observaciones"
                  v-validate="'required|max:200'"
                ></v-textarea>
                <FormError
                  :attribute_name="'create.observaciones'"
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
            <v-toolbar-title>Productos</v-toolbar-title>
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
            color="default lighten-2"
            small
            v-if="!item.deleted_at"
            @click="precios_items(item)"
          >
            Precios
          </v-btn>
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

    <v-col cols="12" md="8">
      <v-dialog
        v-model="dialog_precio"
        persistent
        color="primary"
        transition="dialog-bottom-transition"
      >
        <v-card>
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
          <v-card-title>
            <span class="headline">Agregar Precio</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row class="text-center">
                <v-col cols="12" md="4">
                  <v-row>
                    <v-col cols="12" md="4" class="text-center">
                      <label>Precio</label>
                      <input
                        dark
                        placeholder="Precio"
                        class="nuevo-input"
                        v-model="price_uno.price"
                        @input="price_uno.price = formatear_numero($event)"
                        data-vv-name="precio"
                        v-validate="'required'"
                        data-vv-scope="nuevo_precio"
                      />
                      <FormError
                        :attribute_name="'nuevo_precio.precio'"
                        :errors_form="errors"
                      ></FormError>
                    </v-col>
                    <v-col cols="12" md="4">
                      <label>Descuento %</label>
                      <vue-number-input
                        v-model="price_uno.discount"
                        :min="1"
                        :max="100"
                        controls
                        placeholder="Descuento"
                        data-vv-scope="nuevo_precio"
                        data-vv-name="descuento"
                        v-validate="'required'"
                      ></vue-number-input>
                      <FormError
                        :attribute_name="'nuevo_precio.descuento'"
                        :errors_form="errors"
                      ></FormError>
                    </v-col>

                    <v-col cols="12" md="4">
                      <v-btn
                        color="success"
                        small
                        @click="validar_formulario_precio('nuevo_precio')"
                      >
                        Agregar
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12" md="8">
                  <v-simple-table dark>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-center">Monto</th>
                          <th class="text-center">Descuento %</th>
                          <th class="text-center">Fecha</th>
                        </tr>
                      </thead>
                      <tbody v-if="precios_seleccionados.length > 0">
                        <tr
                          v-for="(item, index) in precios_seleccionados"
                          :key="index"
                        >
                          <td class="text-center">
                            {{ item.monto }}
                          </td>
                          <td class="text-center">{{ item.discount }}</td>
                          <td class="text-center">{{ item.created_at }}</td>
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
            <v-btn color="red darken-1" @click="dialog_precio = false">
              Cerrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
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
import FormError from '../shared/FormError'

export default {
  name: 'Product',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      loading_sub: false,
      dialog_precio: false,
      editedIndex: false,
      search: '',
      headers: [
        {
          text: 'Nombre',
          align: 'start',
          value: 'name',
        },
        {
          text: 'Descripción',
          align: 'start',
          value: 'description',
        },
        {
          text: 'Categoría',
          align: 'start',
          value: 'category.name',
        },
        {
          text: 'Sub Categoría',
          align: 'start',
          value: 'sub_category.name',
        },
        {
          text: 'Descripción',
          align: 'start',
          value: 'description',
        },
        {
          text: 'Monto',
          align: 'start',
          value: 'monto',
        },
        {
          text: 'Descuento %',
          align: 'start',
          value: 'discount',
        },
        {
          text: 'Alerta',
          align: 'start',
          value: 'kardex.notify',
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
      masiva_image: [],
      coins: [],
      categories: [],
      sub_categories: [],
      form: {
        id: 0,
        name: null,
        price: null,
        discount: null,
        description: null,
        category_id: null,
        sub_category_id: null,
        coin_id: null,
        pictures: [],
        notify: null,
      },
      precios_seleccionados: [],
      price_uno: {
        id: null,
        price: null,
        discount: null,
      },
    }
  },
  computed: {
    formTitle() {
      return !this.editedIndex ? 'Agregar producto' : 'Editar producto'
    },
  },

  created() {
    this.initialize()
    this.getCoin()
    this.getCategory()
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
      this.dialog_precio = false

      this.masiva_image = []
      this.$refs.fileupload.value = null
      this.sub_categories = []

      this.form.id = 0
      this.form.name = null
      this.form.price = null
      this.form.discount = null
      this.form.description = null
      this.form.category_id = null
      this.form.sub_category_id = null
      this.form.coin_id = null
      this.form.pictures = []
      this.form.notify = null

      this.price_uno.id = null
      this.price_uno.price = null
      this.price_uno.discount = null

      this.precios_seleccionados = []

      this.$validator.reset()
      this.$validator.reset()
    },

    initialize() {
      this.loading = true

      this.$store.state.services.productService
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
          this.limpiar()
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    mapear(item) {
      this.loading = true
      this.form.id = item.id
      this.form.coin_id = item.coin
      this.form.notify = item.kardex.notify
      this.form.name = item.name
      this.form.description = item.description
      this.form.category_id = item.category
      this.getSubCategory(item.category)
      this.form.sub_category_id = item.sub_category

      this.editedIndex = true
      this.loading = false
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
          this.$store.state.services.productService
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
          this.$store.state.services.productService
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
          this.$store.state.services.productService
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

    //Carga masiva de fotografias
    cargaMasiva(e) {
      this.form.pictures = []
      e.forEach((file) => {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = () => this.form.pictures.push({ photo: reader.result })
      })
    },

    getCoin() {
      this.$store.state.services.coinService
        .index()
        .then((r) => {
          this.coins = r.data.data
        })
        .catch((r) => {})
    },

    getCategory() {
      this.categories = []
      this.$store.state.services.categoryService
        .index()
        .then((r) => {
          r.data.data.forEach((element) => {
            if (element.deleted_at == null) {
              this.categories.push(element)
            }
          })
        })
        .catch((r) => {})
    },

    getSubCategory(item) {
      this.sub_categories = []
      if (item) {
        this.loading_sub = true
        this.$store.state.services.subCategoryService
          .show(item.id)
          .then((r) => {
            this.sub_categories = r.data.data
            this.loading_sub = false
          })
          .catch((r) => {})
      }
    },

    precios_items(item) {
      this.loading = true
      this.price_uno.id = item.id
      this.price_uno.price = null
      this.price_uno.discount = null
      this.precios_seleccionados = item.prices
      this.dialog_precio = true
      this.loading = false
    },

    validar_formulario_precio(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.$swal({
            title: 'Agregar Precio',
            text: '¿Está seguro de realizar esta acción?',
            type: 'success',
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.loading = true
              this.$store.state.services.changePriceService
                .update(this.price_uno)
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
        }
      })
    },
  },
}
</script>
