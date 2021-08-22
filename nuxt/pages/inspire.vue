<template>
    <div>
        <v-card>
            <v-toolbar dark>
                <v-toolbar-title>Topics</v-toolbar-title>
            </v-toolbar>

            <v-list>
                <v-list-group v-for="item in items" :key="item.title" v-model="item.active" prepend-icon="mdi-check" no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item v-for="child in item.items" :key="child.title">
                        <v-list-item-content>
                            <v-list-item-subtitle v-text="child.title"></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
            </v-list>
        </v-card>

        <v-btn @click="dialog = true" style="bottom:70px;" fixed right fab dark>
            <v-icon dark>mdi-plus</v-icon>
        </v-btn>

        <v-dialog v-model="dialog" scrollable>
            <v-card>
                <v-card-title>タイトル</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <ul>
                        <li v-for="(n, i) in 20" :key="i">テキスト{{i}}</li>
                    </ul>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="dialog = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
    data: () => ({
        dialog: false,
        items: [
            {
                title: "Attractions",
                active: false,
                items: [{ title: "List Item" }],
            },
            {
                title: "Dining",
                active: true,
                items: [
                    { title: "Breakfast & brunch" },
                    { title: "New American" },
                    { title: "Sushi" },
                ],
            },
        ],
    }),
};
</script>