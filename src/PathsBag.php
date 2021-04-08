<?php


namespace Kovalovme\Image;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class PathsBag implements Arrayable
{

    protected Model $model;

    /**
     * @var array
     */
    protected array $paths;

    /**
     * PathsBag constructor.
     * @param $model
     * @param string $paths
     */
    public function __construct(Model $model, string $paths)
    {
        $this->model = $model;
        $this->paths = json_decode($paths, true);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function __get(string $name)
    {
        if ($this->checkAttribute($name)) {
            return $this->model->disk . "/" . $this->paths[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param $value
     * @throws Exception
     */
    public function __set(string $name, $value): void
    {
        $this->add($name, $value);
    }

    /**
     * @param string $name
     */
    public function delete(string $name): void
    {
        if ($this->checkAttribute($name)) {
            unset($this->paths[$name]);
        }
    }

    /**
     * @return $this
     */
    public function save(): self
    {
        $this->model->update([
            'paths' => $this->paths
        ]);

        return $this;
    }

    /**
     * @param string $name
     * @param string $path
     * @return PathsBag
     */
    public function add(string $name, string $path): self
    {
        $this->paths = array_merge($this->paths, [$name => $path]);

        return $this;
    }

    /**
     * @param string $name
     */
    public function __unset(string $name): void
    {
        $this->delete($name);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->paths;
    }

    public function __toString(): string
    {
        return json_encode($this->paths);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function checkAttribute(string $name): bool
    {
        if (in_array($name, array_keys($this->paths))) {
            return true;
        } else {
            return false;
        }
    }
}