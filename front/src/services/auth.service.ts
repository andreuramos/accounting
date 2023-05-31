import { ApiAuthService } from '@infrastructure/ApiAuthService'
import { LoginUseCase } from '@application/useCases/Login/LoginUseCase'
import { LoginDTO } from '@application/useCases/Login/LoginDTO'


const login = new LoginUseCase(new ApiAuthService())

export class AuthService {
    static async doAuth(credentials: LoginDTO) {
        try {
            const res = await login.execute(credentials)
            console.log(res)  
        } catch(err) {
            console.error(err)
        }
    }
}