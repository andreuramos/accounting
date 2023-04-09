export type LoginDTO = {
    email: string
    password: string
}

export type LoginDTOResponse = {
    accessToken: string
    refreshToken: string
}