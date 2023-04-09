import { AuthService, AuthTokens } from '@application/services/AuthService'
import { describe, expect, test } from 'vitest'
import { mock } from 'vitest-mock-extended'
import { LoginDTOResponse } from './LoginDTO'
import { LoginUseCase } from './LoginUseCase'


describe(`Login use case`, () => {
    test(`calls service's login method.`, async () => {
       const service = mock<AuthService>()
       const someCredentials = { email: 'foo@bar.com', password: '1234' }
       const login = new LoginUseCase(service)

       await login.execute(someCredentials)

       expect(service.login).toHaveBeenCalledOnce()
    })

    test(`returns error when user or password are incorrect.`, async () => {
        const service = mock<AuthService>()
        const someCredentials = { email: 'foo@bar.com', password: '1234' }
        const login = new LoginUseCase(service)

        const result = await login.execute(someCredentials)

        expect(result.value.errorValue().message).toBe('Wrong user or password.')
    })

    test('returns no error credentials are correct.', async () => {
        const service = mock<AuthService>()
        service.login.mockReturnValue(Promise.resolve(mock<AuthTokens>()))
        const someCredentials = { email: 'foo@bar.com', password: '1234' }
        const login = new LoginUseCase(service)

        const result = await login.execute(someCredentials)

        expect(result.value.errorValue()).toBe(null)
    })
})