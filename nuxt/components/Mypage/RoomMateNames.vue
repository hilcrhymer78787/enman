<template>
    <v-text-field :value="roomMateNames(users)" readonly color="teal" :prepend-icon="prependIcon" :label="label" dense style="font-size:12px;"></v-text-field>
</template>
<script lang="ts">
import Vue, { PropOptions } from "vue";
import { apiUserBearerAuthenticationResponseUserType } from "@/types/api/user/bearerAuthentication/response";
export default Vue.extend({
    props: {
        users: Array as PropOptions<
            apiUserBearerAuthenticationResponseUserType[]
        >,
        prependIcon: String,
        label: String,
    },
    methods: {
        roomMateNames(
            users: apiUserBearerAuthenticationResponseUserType[]
        ): string {
            let outputData = "";
            users.forEach((user, index) => {
                if (index != 0) {
                    outputData = outputData + "、";
                }
                outputData = outputData + user.name;
            });
            return outputData;
        },
    },
});
</script>