import { ApiAuthService } from '@infrastructure/ApiAuthService'
import { LoginUseCase } from '@application/useCases/Login/LoginUseCase'
import { LoginDTO } from '@application/useCases/Login/LoginDTO'


const login = new LoginUseCase(new ApiAuthService())

export class AuthService {
    static async doAuth(credentials: LoginDTO) {
        const result = await login.execute(credentials)
        
        if (result.isLeft()) {
            const error = result.value
            console.log(error)
            return error
        }

        return result.value.getValue()
    }
}