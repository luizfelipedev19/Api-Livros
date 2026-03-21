<?php
class DeleteLivroDTO {
    public int $idLivro;

    public function __construct(array $params)
    {
        $this->idLivro = isset($params['id']) ? (int) $params['id'] : 0;

        $this->validar();
    }

    private function validar(): void {
        if($this->idLivro <0){
            throw new Exception("Id do livro inválido");
        }
    }
}

?>