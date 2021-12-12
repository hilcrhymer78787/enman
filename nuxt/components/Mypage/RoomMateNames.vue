<template>
    <v-text-field :value="roomMateNames(users)" readonly color="teal" :prepend-icon="prependIcon" :label="label" dense style="font-size:12px;"></v-text-field>
</template>
<script lang="ts">
export type userType = {
    id: number;
    name: string;
};
export default {
    props: ["users", "prependIcon", "label"],
    methods: {
        roomMateNames(users: userType[]): string {
            let outputData = "";
            users.forEach((user: userType, index: number): void => {
                if (index != 0) {
                    outputData = outputData + "、";
                }
                outputData = outputData + user.name;
            });
            return outputData;
        },
    },
};
</script>

<style>
</style>