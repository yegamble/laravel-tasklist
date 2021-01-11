<template>
    <v-data-table
        :headers="headers"
        :items="tasks"
        sort-by="calories"
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar
                flat
            >
                <v-toolbar-title>My Tasks</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            New Task
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="12"
                                    >
                                        <v-text-field
                                            v-model="editedItem.description"
                                            label="Task"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="close"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="save"
                            >
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                small
                @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <p>No Tasks</p>
        </template>
    </v-data-table>
</template>

<script>
export default {
    props: {
        'tasks': Array,
    },
    data: () => ({
        dialog: false,
        dialogDelete: false,
        headers: [
            {
                text: '',
                align: 'start',
                sortable: false,
                value: 'editedIndex',
            },
            { text: 'Task', value: 'description' },
            { text: 'Edit/Delete', value: 'actions', sortable: false },
        ],
        tasksArray: [],
        editedIndex: -1,
        editedItem: {
            id:0,
            description:'',
        },
        defaultItem: {
            id:0,
            description:'',
        },
    }),

    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Task' : 'Edit Task'
        },
    },

    watch: {
        dialog (val) {
            val || this.close()
        },
        dialogDelete (val) {
            val || this.closeDelete()
        },
    },

    created () {
        this.initialize()
    },

    methods: {
        initialize () {
            this.tasksArray = this.tasks
        },

        editItem (item) {
            this.editedIndex = this.tasksArray.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem (item) {
            this.editedIndex = this.tasksArray.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm () {
            this.tasks.splice(this.editedIndex, 1)
            this.closeDelete()
        },

        close () {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {

                this.editedIndex = -1

                axios.delete('/task/'+this.editedItem.id,
                    this.editedItem,
                    {
                        headers: {
                            'Content-Type' : 'application/json'
                        }
                    }
                )
                    .then(response => {

                        this.close()
                        console.log(this.info);
                        this.formHasSuccess = true
                    })
                    .catch(error => {
                        this.formHasErrors = true
                        console.log(error)
                    });

                this.editedItem = Object.assign({}, this.defaultItem)
            })
        },

        save () {
            if (this.editedIndex > -1) {

                axios.put('/task/'+this.editedItem.id,
                    this.editedItem,
                    {
                        headers: {
                            'Content-Type' : 'application/json'
                        }
                    }
                )
                    .then(response => {

                        this.close()
                        console.log(this.info);
                        this.formHasSuccess = true
                    })
                    .catch(error => {
                        this.formHasErrors = true
                        console.log(error)
                    });

                Object.assign(this.tasksArray[this.editedIndex], this.editedItem)

            } else {

                axios.post('/task/',
                    this.editedItem,
                    {
                        headers: {
                            'Content-Type' : 'application/json'
                        }
                    }
                )
                    .then(response => {
                        // Object.assign(response.data)
                        this.editedItem.id = response.data.id
                        this.editedItem.description = response.data.description
                        this.tasksArray.push(this.editedItem)
                        this.close()
                        console.log(this.info);
                        this.formHasSuccess = true
                    })
                    .catch(error => {
                        this.formHasErrors = true
                        console.log(error)
                    });

            }
            this.close()
        },
    },
}
</script>
