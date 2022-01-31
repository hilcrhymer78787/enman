// import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios"
export default function ({ $axios, $cookies }) {
    $axios.onRequest((config) => {
        config.headers.token = $cookies.get("token")
    })
    $axios.onError((error) => {
        if(error.response){
            console.error(error.response);
        }
    })
}