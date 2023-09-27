import { AuthService, AuthTokens } from '@application/services/AuthService'
import { LoginDTO } from '@application/useCases/Login/LoginDTO'


export class ApiAuthService implements AuthService {
    async login(credentials: LoginDTO): Promise<AuthTokens> {
        const res = await fetch('/api/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        })

        return res.json() as Promise<AuthTokens>
    }
}