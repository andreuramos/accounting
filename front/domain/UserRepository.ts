import { User } from './User'

export default interface UserRepository {
    add(user: User): Promise<void>
    findByEmail(id: string): Promise<User | null>
}