import { LoginDTO } from '@application/useCases/Login/LoginDTO'

export type AuthTokens = {
    accessToken: string
    refreshToken: string
}

export interface AuthService {
    login(credentials: LoginDTO): Promise<AuthTokens>
}