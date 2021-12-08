export default function ({ $axios, $cookies }) {
    $axios.onRequest((config) => {
        config.headers.token = $cookies.get("token")
        return config
    })
}