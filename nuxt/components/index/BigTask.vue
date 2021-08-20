<template>
    <div>
        <span v-for="n in 1000" :key="n">あ</span>

        <v-btn @click="dialog = true" style="bottom:70px;" fixed right fab dark>
            <v-icon dark>mdi-plus</v-icon>
        </v-btn>

        <v-dialog v-model="dialog" scrollable>
            <v-card>
                <v-card-title dark>add new task</v-card-title>
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
                    <v-btn @click="dialog = false">Add</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
        };
    },
};
</script>

<style>
</style>