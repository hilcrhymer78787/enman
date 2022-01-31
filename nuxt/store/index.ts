import { ActionTree, MutationTree } from 'vuex'
import { apiWorkReadCalendarRequestType } from '@/types/api/work/read/calendar/request'
import { apiUserBearerAuthenticationResponseType } from '@/types/api/user/bearerAuthentication/response'
import { apiWorkReadCalendarResponseType } from '@/types/api/work/read/calendar/response'
import { apiWorkReadCalendarResponseCalendarType } from '@/types/api/work/read/calendar/response'
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
import axios from 'axios'
const CancelToken = axios.CancelToken;
let setCalendarsCancel: any = null;
let setLoginInfoByTokenCancel: any = null;
export type RootState = ReturnType<typeof state>

export const state = () => ({
    loginInfo: null as apiUserBearerAuthenticationResponseType | null,
    calendars: [] as apiWorkReadCalendarResponseCalendarType[],
})
export const mutations: MutationTree<RootState> = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setCalendars(state, calendars) {
        state.calendars = calendars
    },
}

export const actions: ActionTree<RootState, RootState> = {
    setTokenRedirect({ }, token) {
        this.$cookies.set("token", token, {
            maxAge: 60 * 60 * 24 * 30,
        });
        this.$router.push("/");
    },
    setLoginInfoByToken({ commit, dispatch }) {
        if (setLoginInfoByTokenCancel) {
            setLoginInfoByTokenCancel()
        }
        const requestConfig: AxiosRequestConfig = {
            url: `/api/user/bearer_authentication`,
            method: "GET",
            cancelToken: new CancelToken(c => {
                setLoginInfoByTokenCancel = c
            }),
        };
        this.$axios(requestConfig)
            .then((res: AxiosResponse<apiUserBearerAuthenticationResponseType>) => {
                // トークンが有効
                if (this.$cookies.get("token")) {
                    if ((this.$router.currentRoute.name == 'login' || this.$router.currentRoute.name == 'login-newUser')) {
                        this.$router.push("/");
                    }
                    commit('setLoginInfo', res.data)
                }
            })
            .catch((err: AxiosError) => {
                if (err.response) {
                    dispatch('logout')
                }
            })
    },
    logout({ commit }) {
        this.$cookies.remove("token");
        if (!(this.$router.currentRoute.name == 'login' || this.$router.currentRoute.name == 'login-newUser')) {
            this.$router.push("/login");
        }
        commit('setLoginInfo', false)
    },
    async setCalendars({ commit }) {
        if (setCalendarsCancel) {
            setCalendarsCancel()
        }
        let apiParam: apiWorkReadCalendarRequestType = {
            year: Number(this.$router.currentRoute.query.year),
            month: Number(this.$router.currentRoute.query.month)
        }
        const requestConfig: AxiosRequestConfig = {
            url: `/api/work/read/calendar`,
            method: "GET",
            params: apiParam,
            cancelToken: new CancelToken(c => {
                setCalendarsCancel = c
            }),
        };
        await this.$axios(requestConfig)
            .then((res: AxiosResponse<apiWorkReadCalendarResponseType>) => {
                commit("setCalendars", res.data.calendars);
            })
            .catch((err: AxiosError) => {
                console.error(err.response)
            })
    },
}