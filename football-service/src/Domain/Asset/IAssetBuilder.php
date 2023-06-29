<?php


namespace Sportal\FootballApi\Domain\Asset;


interface IAssetBuilder
{
    public function build(IAssetEntity $assetEntity): IAssetModel;
}