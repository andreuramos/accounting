import { User } from '@domain/User'
import UserRepository from '@domain/UserRepository'

export class InMemoryUserRepository implements UserRepository {
    public async add(user: User): Promise<Response> {
        return fetch('localhost:8080/user', {
            method: 'POST',
            body: JSON.stringify(user)
        })
    }

    public async findByEmail(email: string): Promise<User | null> {
        return null
    }

    public async exists(email: string): Promise<boolean> {
        return true
    }
}