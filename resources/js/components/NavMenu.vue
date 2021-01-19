<template>
    <div>
        <v-toolbar flat>
            <v-list>
                <v-list-item>
                    <v-list-item-title class="title">
                        Menu
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-toolbar>
        <v-divider></v-divider>
        <v-list>
            <v-list-item
                value="true"
                v-for="(item, i) in items"
                :key="i"
                :to="item.route.link"
            >
                <v-list-item-content>
                    <v-list-item-title v-text="item.title"></v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                items: [
                    { title: 'Company', route: { name: 'company' , 'link' : '/company'} }
                ]
            }
        }
    }
</script>
